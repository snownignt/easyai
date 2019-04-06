<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Kernel\Http;


use EasyAi\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 * @package EasyAi\Kernel\Http
 */
class Response extends \GuzzleHttp\Psr7\Response
{
    /**
     * @date 2019/3/27 13:54
     * @return string
     */
    public function getBodyContents()
    {
        $this->getBody()->rewind();
        $contents = $this->getBody()->getContents();
        $this->getBody()->rewind();
        return $contents;
    }

    /**
     * @param ResponseInterface $response
     * @return Response
     * @author lichengjun
     * @date 2019-04-06 22:46
     */
    public static function buildFromPsrResponse(ResponseInterface $response)
    {
        return new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }

    /**
     * json response
     * @date 2019/3/27 14:04
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * array response
     * @date 2019/3/27 14:04
     * @return array
     */
    public function toArray()
    {
        $content = $this->removeControlCharacters($this->getBodyContents());
        $array = json_decode($content, true, 512, JSON_BIGINT_AS_STRING);
        if (JSON_ERROR_NONE === json_last_error()) {
            return (array)$array;
        }

        return [];
    }


    /**
     * collection response
     * @date 2019/3/27 14:04
     * @return Collection
     */
    public function toCollection()
    {
        return new Collection($this->toArray());
    }

    /**
     * object response
     * @date 2019/3/27 14:05
     * @return mixed
     */
    public function toObject()
    {
        return json_decode($this->toJson());
    }


    /**
     * to string
     * @date 2019/3/27 14:05
     * @return string
     */
    public function __toString()
    {
        return $this->getBodyContents();
    }

    /**
     * @date 2019/3/27 14:05
     * @param string $content
     * @return string|string[]|null
     */
    protected function removeControlCharacters(string $content)
    {
        return \preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', $content);
    }
}