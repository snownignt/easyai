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

use EasyAi\Kernel\Contracts\ArrayAble;
use EasyAi\Kernel\Exceptions\InvalidArgumentException;
use EasyAi\Kernel\Http\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait ResponseCastable
 * @package EasyAi\Kernel\Traits
 */
trait ResponseCastable
{
    /**
     * @date 2019/3/27 14:10
     * @param ResponseInterface $response
     * @param null $type
     * @return array|Response|\EasyAi\Kernel\Support\Collection|mixed|ResponseInterface
     * @throws InvalidArgumentException
     */
    protected function castResponseToType(ResponseInterface $response, $type = null)
    {
        $response = Response::buildFromPsrResponse($response);
        $response->getBody()->rewind();
        switch ($type ?? "array") {
            case "collection":
                return $response->toCollection();
            case "array":
                return $response->toArray();
            case "object":
                return $response->toObject();
            case"raw":
                return $response;
            default:
                if (!is_subclass_of($type, ArrayAble::class)) {
                    throw new InvalidArgumentException(sprintf(
                            "config key 'response_type' classname must be an instanceof %s",
                            ArrayAble::class
                        )
                    );
                }
        }
        return new $type($response);
    }

    /**
     * @date 2019/3/27 14:15
     * @param $response
     * @param null $type
     * @return array|Response|\EasyAi\Kernel\Support\Collection|mixed|ResponseInterface
     * @throws InvalidArgumentException
     */
    public function detectAndCastResponseToType($response, $type = null)
    {
        switch (true) {
            case $response instanceof ResponseInterface:
                $response = Response::buildFromPsrResponse($response);
                break;
            case $response instanceof ArrayAble:
                $response = new Response(200, [], json_encode($response->toArray()));
                break;
            case is_scalar($response):
                $response = new Response(200, [], $response);
                break;
            default:
                throw new InvalidArgumentException(sprintf("Unsupported response type %s", gettype($response)));
        }
        return $this->castResponseToType($response, $type);
    }
}