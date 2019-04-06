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
use EasyAi\Kernel\Exceptions\HttpException;
use EasyAi\Kernel\Exceptions\InvalidArgumentException;
use EasyAi\Kernel\Exceptions\RuntimeException;
use EasyAi\Kernel\Traits\HasHttpRequests;
use EasyAi\Kernel\Traits\InteractsWithCache;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AccessToken
 * @package EasyAi\Kernel
 */
abstract class AccessToken implements AccessTokenInterface
{

    use HasHttpRequests, InteractsWithCache;

    /**
     * @var Container
     */
    protected $app;

    /**
     * @var string
     */
    protected $requestMethod = 'GET';

    /**
     * @var
     */
    protected $endpointToGetToken;

    /**
     * @var
     */
    protected $queryName;

    /**
     * @var
     */
    protected $token;

    /**
     * @var int
     */
    protected $safeSeconds = 500;

    /**
     * @var string
     */
    protected $tokenKey = 'access_token';

    /**
     * @var string
     */
    protected $cachePrefix = 'easy.kernel.access_token';

    /**
     * AccessToken constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     *  get refresh token
     * @date 2019/3/28 10:47
     * @return array
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getRefreshedToken(): array
    {
        return $this->getToken(true);
    }

    /**
     * get token
     * @date 2019/3/28 10:46
     * @param bool $refresh
     * @return array
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getToken(bool $refresh = false): array
    {
        $cacheKey = $this->getCacheKey();
        $cache = $this->getCache();

        if (!$refresh && $cache->has($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $token = $this->requestToken($this->getCredentials(), true);
        $this->setToken($token[$this->tokenKey], $token['expire_in'] ?? 30 * 24 * 3600);
        return $token;
    }

    /**
     * set token
     * @date 2019/3/28 10:40
     * @param string $token
     * @param int $lifetime
     * @return AccessTokenInterface
     * @throws RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setToken(string $token, int $lifetime = 7200): AccessTokenInterface
    {
        $this->getCache()->set($this->getCacheKey(), [
            $this->tokenKey => $token,
            'expire_in' => $lifetime
        ], $lifetime - $this->safeSeconds);

        if (!$this->getCache()->has($this->getCacheKey())) {
            throw new RuntimeException("Failed to cache access token.");
        }
        return $this;
    }

    /**
     * refresh token
     * @date 2019/3/28 10:47
     * @return AccessTokenInterface
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function refresh(): AccessTokenInterface
    {
        $this->getToken(true);
        return $this;
    }

    /**
     * @date 2019/3/28 10:46
     * @param array $credentials
     * @param bool $toArray
     * @return array|Http\Response|Support\Collection|mixed|ResponseInterface
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestToken(array $credentials, $toArray = false)
    {
        $response = $this->sendRequest($credentials);
        $result = json_decode($response->getBody()->getContents(), true);
        $formatted = $this->castResponseToType($response, $this->app['config']->get('response_type'));
        if (empty($result[$this->tokenKey])) {
            throw new HttpException();
        }
        return $toArray ? $result : $formatted;
    }

    /**
     * @date 2019/3/28 10:47
     * @param RequestInterface $request
     * @param array $requestOption
     * @return RequestInterface
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function applyToRequest(RequestInterface $request, array $requestOption = []): RequestInterface
    {
        parse_str($request->getUri()->getQuery(), $query);
        $query = http_build_query(array_merge($this->getQuery(), $query));
        return $request->withUri($request->getUri()->withQuery($query));
    }


    /**
     * @date 2019/3/28 10:46
     * @param array $credentials
     * @return ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendRequest(array $credentials): ResponseInterface
    {
        $options = [
            ("GET" === $this->requestMethod) ? "query" : "json" => $credentials
        ];
        return $this->setHttpClient($this->app['http_client'])->request($this->getEndpoint(), $this->requestMethod, $options);
    }

    /**
     * @date 2019/3/27 13:46
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cachePrefix . md5(json_encode($this->getCredentials()));
    }

    /**
     * @date 2019/3/28 10:47
     * @return array
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function getQuery(): array
    {
        return [$this->queryName ?? $this->tokenKey => $this->getToken()[$this->tokenKey]];
    }

    /**
     * @date 2019/3/27 13:46
     * @return string
     * @throws InvalidArgumentException
     */
    public function getEndpoint(): string
    {
        if (empty($this->endpointToGetToken)) {
            throw new InvalidArgumentException();
        }
        return $this->endpointToGetToken;
    }

    /**
     * @date 2019/3/27 13:46
     * @return string
     */
    public function getTokenKey()
    {
        return $this->tokenKey;
    }

    /**
     * @date 2019/3/27 13:46
     * @return array
     */
    abstract protected function getCredentials(): array;

}