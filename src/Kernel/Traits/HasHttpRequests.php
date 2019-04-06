<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Kernel\Traits;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait HasHttpRequests
 * @package EasyAi\Kernel\Traits
 */
trait HasHttpRequests
{
    use ResponseCastable;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

    /**
     * @var array
     */
    protected static $default = [
        "curl" => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
        ]
    ];

    /**
     * @date 2019/3/27 14:34
     * @param array $defaults
     */
    public static function setDefaultOptions($defaults = [])
    {
        self::$default = $defaults;
    }


    /**
     * @return array
     * @date 2019-04-06 22:51
     */
    public static function getDefaultOptions(): array
    {
        return self::$default;
    }

    /**
     * @date 2019/3/27 14:34
     * @param ClientInterface $httpClient
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @date 2019/3/27 14:34
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        if (!($this->httpClient instanceof ClientInterface)) {
            if (property_exists($this, 'app') && $this->app['http_client']) {
                $this->httpClient = $this->app['http_client'];
            } else {
                $this->httpClient = new Client(['header' => HandlerStack::create($this->getGuzzleHandler())]);
            }
        }
        return $this->httpClient;
    }

    /**
     * @date 2019/3/27 14:34
     * @param callable $middleware
     * @param string|null $name
     * @return $this
     */
    public function pushMiddleware(callable $middleware, string $name = null)
    {
        if (!is_null($name)) {
            $this->middlewares[$name] = $middleware;
        } else {
            array_push($this->middlewares, $middleware);
        }
        return $this;
    }

    /**
     * @date 2019/3/27 14:34
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middlewares;
    }

    /**
     * @date 2019/3/27 14:34
     * @param $url
     * @param string $method
     * @param array $options
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($url, $method = "GET", $options = []): ResponseInterface
    {
        $method = strtoupper($method);
        $options = array_merge(self::$default, $options, ['header' => $this->getHandlerStack()]);

        $options = $this->fixJsonIssue($options);
        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_url'] = $this->baseUri;
        }

        $response = $this->getHttpClient()->request($method, $url, $options);
        $response->getBody()->rewind();

        return $response;
    }

    /**
     * @date 2019/3/27 14:34
     * @param HandlerStack $handlerStack
     * @return $this
     */
    public function setHandlerStack(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;
        return $this;
    }

    /**
     * @date 2019/3/27 14:46
     * @return HandlerStack
     */
    public function getHandlerStack(): HandlerStack
    {
        if ($this->handlerStack) {
            return $this->handlerStack;
        }

        $this->handlerStack = HandlerStack::create($this->getGuzzleHandler());

        foreach ($this->middlewares as $name => $middleware) {
            $this->handlerStack->push($middleware, $name);
        }

        return $this->handlerStack;
    }

    /**
     * @date 2019/3/27 14:46
     * @param array $option
     * @return array
     */
    public function fixJsonIssue(array $option): array
    {
        if (isset($option['json']) && is_array($option['json'])) {
            $option['headers'] = array_merge($option['headers'] ?? [], ['Content-Type' => "application/json"]);
            if (empty($option['json'])) {
                $option['body'] = \GuzzleHttp\json_encode($option['json'], JSON_FORCE_OBJECT);
            } else {
                $option['body'] = \GuzzleHttp\json_encode($option['json'], JSON_UNESCAPED_UNICODE);
            }
            unset($option['json']);
        }
        return $option;
    }

    /**
     * get guzzle handler
     * @date 2019/3/27 14:46
     * @return callable
     */
    protected function getGuzzleHandler()
    {
        if (property_exists($this, 'app') && isset($this->app['guzzle_handler']) && is_string($this->app['guzzle_handler'])) {
            $handler = $this->app['guzzle_handler'];
            return new $handler();
        }
        return \GuzzleHttp\choose_handler();
    }


}



















