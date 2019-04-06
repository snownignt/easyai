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


/**
 * Trait JsonRequest
 * @package EasyAi\Kernel\Traits
 */
trait JsonRequest
{
    /**
     * @param $file
     * @param $url
     * @param array $options
     * @param bool $isUrl
     * @param string $imageField
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:16
     */
    public function basic($file, $url, array $options = [], $isUrl = true, $imageField = 'image')
    {
        if ($isUrl) {
            return $this->checkByUrl($file, $url, $options, $imageField);
        }
        return $this->checkByImage($file, $url, $options, $imageField);
    }

    /**
     * @param $file
     * @param $url
     * @param array $options
     * @param string $imageField
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:16
     */
    protected function checkByUrl($file, $url, array $options = [], $imageField = 'image')
    {
        $data = [];
        $data[$imageField] = $file;
        $options = array_merge($data, $options);
        return $this->httpPostJson($url, $options);
    }

    /**
     * @param $file
     * @param $url
     * @param array $options
     * @param string $imageField
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:16
     */
    protected function checkByImage($file, $url, array $options = [], $imageField = 'image')
    {
        $data = [];
        $data[$imageField] = base64_encode($file);
        $options = array_merge($data, $options);
        return $this->httpPostJson($url, $options);
    }

    /**
     * @param $file
     * @param $url
     * @param array $options
     * @param string $imageField
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:16
     */
    protected function checkByToken($file, $url, array $options = [], $imageField = 'image')
    {
        $data = [];
        $data[$imageField] = $file;
        $options = array_merge($data, $options);
        return $this->httpPostJson($url, $options);
    }
}