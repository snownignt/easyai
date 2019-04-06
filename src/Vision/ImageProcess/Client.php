<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Vision\ImageProcess;


use EasyAi\Kernel\BaseClient;

/**
 * Class Client
 * @package EasyAi\Vision\ImageProcess
 */
class Client extends BaseClient
{
    /**
     * @param string $image
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/2 11:01
     */
    public function imageQualityEnhance(string $image)
    {
        return $this->custom('rest/2.0/image-process/v1/image_quality_enhance', $image);
    }

    /**
     * @param string $image
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/2 11:01
     */
    public function fogRemove(string $image)
    {
        return $this->custom('rest/2.0/image-process/v1/dehaze', $image);
    }

    /**
     * @param string $image
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/2 11:05
     */
    public function contrastEnhance(string $image)
    {
        return $this->custom('rest/2.0/image-process/v1/image_quality_enhance', $image);
    }

    /**
     * @param string $image
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/2 11:05
     */
    public function colorize(string $image)
    {
        return $this->custom('rest/2.0/image-process/v1/image_quality_enhance', $image);
    }

    /**
     * @param string $image
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/2 11:05
     */
    public function stretchRestore(string $image)
    {
        return $this->custom('rest/2.0/image-process/v1/stretch_restore', $image);
    }

    /**
     * @param string $url
     * @param string $image
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/2 11:09
     */
    protected function custom(string $url, string $image)
    {
        $options['image'] = base64_encode($image);
        return $this->httpPost($url, $options);
    }
}