<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Vision\ImageAudit;


use EasyAi\Kernel\BaseClient;
use EasyAi\Kernel\Traits\JsonRequest;

/**
 * Class Client
 * @package EasyAi\Vision\ImageAudit
 */
class Client extends BaseClient
{
    use JsonRequest;

    /**
     * @param string $image
     * @param array $scenes
     * @param bool $isUrl
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:33
     */
    public function imageCensor(string $image, array $scenes, bool $isUrl = true, array $options = [])
    {
        $options = array_merge(['scenes' => $scenes], $options);
        return $this->basic($image, 'api/v1/solution/direct/img_censor', $options, $isUrl, 'imgUrl');
    }

    /**
     * @param string $image
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:25
     */
    public function detectGif(string $image)
    {
        return $this->basic($image, 'rest/2.0/antiporn/v1/detect_gif', [], false);
    }

    /**
     * @param string $images
     * @param bool $isUrl
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 16:03
     */
    public function faceAudit(string $images, $isUrl = true)
    {
        $options = [];
        if (!$isUrl) {
            $options['images'] = base64_encode($images);
        } else {
            $options['imgUrls'] = urlencode($images);
        }
        return $this->httpPost('rest/2.0/solution/v1/face_audit', $options);
    }

    /**
     * @param array $scenes
     * @param array $imgUrls
     * @param string $extTag
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 16:13
     */
    public function videoCensor(array $scenes, array $imgUrls, string $extTag)
    {
        $options = [
            'app_id' => $this->app->config->app_id,
            'imgUrls' => urlencode(implode(',', $imgUrls)),
            'scenes' => $scenes,
            'extTag' => $extTag
        ];
        return $this->httpPostJson('rest/2.0/solution/v1/video_censor/', $options);
    }

    /**
     * @param $url
     * @param $correct
     * @param array $options
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 17:13
     */
    public function report($url, $correct, $options = [])
    {
        $options = array_merge([
            'api_url' => $url,
            'correct' => $correct
        ], $options);
        return $this->httpPostJson('rpc/2.0/feedback/v1/report', $options);
    }
}