<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Kernel;


use EasyAi\Kernel\Contracts\AccessTokenInterface;
use EasyAi\Kernel\Http\Response;
use EasyAi\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseClient
 * @package EasyAi\Kernel
 */
class BaseClient
{
    use HasHttpRequests {
        request as performRequest;
    }

    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * @var AccessTokenInterface|mixed|null
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $baseUri;

    /**
     * BaseClient constructor.
     * @param ServiceContainer $app
     * @param AccessTokenInterface|null $accessToken
     */
    public function __construct(ServiceContainer $app, AccessTokenInterface $accessToken = null)
    {
        $this->app = $app;
        $this->accessToken = $accessToken ?? $this->app['access_token'];
    }

    /**
     * @param string $url
     * @param array $query
     * @return array|Response|Support\Collection|mixed|ResponseInterface
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @date 2019/3/29 10:33
     */
    public function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array|Response|Support\Collection|mixed|ResponseInterface
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @date 2019/3/29 10:33
     */
    public function httpPost(string $url, array $data = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data]);
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $query
     * @return array|Response|Support\Collection|mixed|ResponseInterface
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @date 2019/3/29 10:33
     */
    public function httpPostJson(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'json' => $data]);
    }

    /**
     * @param string $url
     * @param array $files
     * @param array $form
     * @param array $query
     * @return array|Response|Support\Collection|mixed|ResponseInterface
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @date 2019/3/29 10:34
     */
    public function httpUpload(string $url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r')
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', ['query' => $query, 'multipart' => $multipart, 'connect_timeout' => 30, 'read_timeout' => 30]);
    }

    /**
     * @return AccessTokenInterface
     * @date 2019/3/28 14:49
     */
    public function getAccessToken(): AccessTokenInterface
    {
        return $this->accessToken;
    }

    /**
     * @param AccessTokenInterface $accessToken
     * @return $this
     * @date 2019/3/28 14:49
     */
    public function setAccessToken(AccessTokenInterface $accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @param $url
     * @param string $method
     * @param array $options
     * @param bool $returnRaw
     * @return array|Response|Support\Collection|mixed|ResponseInterface
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @date 2019/3/29 10:33
     */
    public function request($url, $method = "GET", $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }
        $options = $this->appendAccessTokenToQuery($options);

        $response = $this->performRequest($url, $method, $options);
        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }


    /**
     * @date 2019/3/29 10:41
     * @param array $options
     * @return array
     */
    public function appendAccessTokenToQuery(array $options)
    {
        $accessToken = $this->accessToken->getToken()['access_token'] ?? '';
        if (isset($options['query'])) {
            $options['query'] = array_merge(['access_token' => $accessToken], $options['query']);
        } else {
            $options['query'] = ['access_token' => $accessToken];
        }
        return $options;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @return Response
     * @throws Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @date 2019/3/29 10:34
     */
    public function requestRaw(string $url, string $method = "GET", array $options = [])
    {
        return Response::buildFromPsrResponse($this->request($url, $method, $options, true));
    }

    /**
     * @date 2019/3/28 14:49
     */
    protected function registerHttpMiddlewares()
    {
        $this->pushMiddleware($this->retryMiddleware(), 'retry');

        $this->pushMiddleware($this->accessTokenMiddleware(), 'access_token');

        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * @return \Closure
     * @date 2019/3/28 14:49
     */
    public function accessTokenMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if ($this->accessToken) {
                    $request = $request->accessToken->applyToRequest($request, $options);
                }
                return $handler($request, $options);
            };
        };
    }

    /**
     * @return callable
     * @date 2019/3/28 14:49
     */
    public function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);
        return Middleware::log($this->app['logger'], $formatter);
    }

    /**
     * @return callable
     * @date 2019/3/28 14:49
     */
    public function retryMiddleware()
    {
        return Middleware::retry(function ($retries, RequestInterface $request, ResponseInterface $response) {
            if ($retries < $this->app->config->get('http.max_retries', 1) && $response && $body = $response->getBody()) {
                $response = json_decode($body, true);
                if (!empty($response['error']) && in_array(abs($response['error']), [100, 110, 111])) {
                    $this->accessToken->refresh();
                    $this->app['logger']->debug("Retrying with refreshed access token");
                    return true;
                }
            }
            return false;
        }, function () {
            return abs($this->app->config->get('http.retry_delay', 500));
        });
    }
}

















