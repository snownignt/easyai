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
 * Trait NormalRequest
 * @package EasyAi\Kernel\Traits
 */
trait NormalRequest
{
    /**
     * @date 2019/4/1 14:05
     * @param $file
     * @param $url
     * @param array $options
     * @param bool $isUrl
     * @return mixed
     */
    public function basic($file, $url, array $options = [], $isUrl = true)
    {
        if ($isUrl) {
            return $this->checkByUrl($file, $url, $options);
        }
        return $this->checkByImage($file, $url, $options);
    }

    /**
     * @param $file
     * @param $url
     * @param array $options
     * @return mixed
     * @date 2019/4/1 13:52
     */
    protected function checkByUrl($file, $url, array $options = [])
    {
        $data = [];
        $data['url'] = $file;
        $options = array_merge($data, $options);
        return $this->httpPost($url, $options);
    }

    /**
     * @param $file
     * @param $url
     * @param array $options
     * @return mixed
     * @date 2019/4/1 13:52
     */
    protected function checkByImage($file, $url, array $options = [])
    {
        $data = [];
        $data['image'] = base64_encode($file);
        $options = array_merge($data, $options);
        return $this->httpPost($url, $options);
    }

}