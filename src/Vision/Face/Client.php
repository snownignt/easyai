<?php
/*
 * This file is part of the easyai package.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EasyAi\Vision\Face;


use EasyAi\Kernel\BaseClient;
use EasyAi\Kernel\Exceptions\InvalidArgumentException;
use EasyAi\Kernel\Traits\JsonRequest;

/**
 * Class Client
 * @package EasyAi\Vision\Face
 */
class Client extends BaseClient
{
    use JsonRequest;

    /**
     * @param $image
     * @param string $imageType
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:11
     */
    public function detect($image, $imageType = 'URL', array $options = [])
    {
        return $this->customCheck($image, 'rest/2.0/face/v3/detect', $imageType, $options);
    }

    /**
     * @param array $images
     * @param string $imageType
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:11
     */
    public function match(array $images, $imageType = 'URL', array $options = [])
    {
        $image = implode(',', $images);
        return $this->customCheck($image, 'rest/2.0/face/v3/match', $imageType, $options);
    }

    /**
     * @param string $image
     * @param string $imageType
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:11
     */
    public function search(string $image, string $imageType = 'URL', array $options = [])
    {
        return $this->customCheck($image, 'rest/2.0/face/v3/search', $imageType, $options);
    }

    /**
     * @param string $image
     * @param string $groupIdList
     * @param string $imageType
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:11
     */
    public function multiSearch(string $image, string $groupIdList, string $imageType = 'URL', array $options = [])
    {
        $options = array_merge(['group_id_list' => $groupIdList], $options);
        return $this->customCheck($image, 'rest/2.0/face/v3/multi-search', $imageType, $options);
    }

    /**
     * @param $image
     * @param $imageType
     * @param $groupId
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:11
     */
    public function add($image, $imageType, $groupId, array $options = [])
    {
        $options = array_merge(['group_id' => $groupId], $options);
        return $this->customCheck($image, 'rest/2.0/face/v3/faceset/user/add', $imageType, $options);
    }

    /**
     * @param $image
     * @param $imageType
     * @param $groupId
     * @param $userId
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:11
     */
    public function update($image, $imageType, $groupId, $userId, array $options = [])
    {
        $options = array_merge([
            'group_id' => $groupId,
            'user_id' => $userId
        ], $options);
        return $this->customCheck($image, 'rest/2.0/face/v3/faceset/user/update', $imageType, $options);
    }

    /**
     * @param $groupId
     * @param $userId
     * @param array $options
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function faceDelete($groupId, $userId, array $options = [])
    {
        $options = array_merge([
            'group_id' => $groupId,
            'user_id' => $userId
        ], $options);
        return $this->httpPostJson('rest/2.0/face/v3/faceset/face/delete', $options);
    }

    /**
     * @param $groupId
     * @param $userId
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function getList($groupId, $userId)
    {
        $options = [
            'group_id' => $groupId,
            'user_id' => $userId
        ];
        return $this->httpPostJson('rest/2.0/face/v3/faceset/face/getlist', $options);
    }

    /**
     * @param $groupId
     * @param int $start
     * @param int $length
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function getUsers($groupId, $start = 0, $length = 1)
    {
        $options = [
            'group_id' => $groupId,
            'start' => $start,
            'length' => $length
        ];
        return $this->httpPostJson('rest/2.0/face/v3/faceset/group/getusers', $options);
    }

    /**
     * @param $userId
     * @param $srcGroup
     * @param $toGroup
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function copy($userId, $srcGroup, $toGroup)
    {
        $options = [
            'user_id' => $userId,
            'src_group_id' => $srcGroup,
            'dst_group_id' => $toGroup
        ];
        return $this->httpPostJson('rest/2.0/face/v3/faceset/user/copy', $options);
    }

    /**
     * @param $userId
     * @param $groupId
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function userDelete($userId, $groupId)
    {
        $options = [
            'user_id' => $userId,
            'group_id' => $groupId
        ];
        return $this->httpPostJson('rest/2.0/face/v3/faceset/user/delete', $options);
    }

    /**
     * @param $groupId
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function groupAdd($groupId)
    {
        $options = [
            'group_id' => $groupId
        ];
        return $this->httpPostJson('rest/2.0/face/v3/faceset/group/add', $options);
    }

    /**
     * @param $groupId
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function groupDelete($groupId)
    {
        $options = [
            'group_id' => $groupId
        ];
        return $this->httpPostJson('rest/2.0/face/v3/faceset/group/delete', $options);
    }

    /**
     * @param int $start
     * @param int $length
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function groupList($start = 0, $length = 10)
    {
        $options = [
            'start' => $start,
            'length' => $length
        ];
        return $this->httpPostJson('rest/2.0/face/v3/faceset/group/getlist', $options);
    }

    /**
     * @param string $image
     * @param string $imageType
     * @param int $idCardNumber
     * @param $name
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function personVerify(string $image, string $imageType, int $idCardNumber, $name, array $options = [])
    {
        $options = array_merge([
            'id_card_number' => $idCardNumber,
            'name' => $name
        ], $options);
        return $this->customCheck($image, 'rest/2.0/face/v3/person/verify', $imageType, $options);
    }

    /**
     * @param $idCardNumber
     * @param $name
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:12
     */
    public function idMatch($idCardNumber, $name)
    {
        $data = ['id_card_number' => $idCardNumber, 'name' => $name];
        return $this->checkByUrl($data, 'rest/2.0/face/v3/person/idmatch');
    }

    /**
     * live detection
     * @param array $images
     * @param string $imageType
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 15:13
     */
    public function faceVerify(array $images, string $imageType, array $options = [])
    {
        $images = implode(',', $images);
        return $this->customCheck($images, 'rest/2.0/face/v3/faceverify', $imageType, $options);
    }

    /**
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @date 2019/4/1 11:33
     */
    public function voiceSessionCode()
    {
        return $this->httpPostJson('rest/2.0/face/v1/faceliveness/sessioncode', ['appid' => $this->app->config->app_id]);
    }

    /**
     * @param $video
     * @param $options
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:13
     */
    public function faceLivenessVerify($video, $options)
    {
        $options = array_merge(['video_base64' => base64_encode($video)], $options);
        return $this->httpPostJson('rest/2.0/face/v1/faceliveness/verify', $options);
    }

    /**
     * @param array $imageTemplate
     * @param array $imageTarget
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author snownight
     * @date 2019/4/1 15:13
     */
    public function faceMerge(array $imageTemplate, array $imageTarget)
    {
        if (!array_key_exists('image_template', $imageTemplate)) {
            $imageTemplate = ['image_template' => $imageTemplate];
        }
        if (!array_key_exists('image_target', $imageTarget)) {
            $imageTarget = ['image_target' => $imageTarget];
        }
        $options = array_merge($imageTarget, $imageTemplate);
        return $this->httpPostJson('rest/2.0/face/v1/merge', $options);
    }

    /**
     * @param $file
     * @param $url
     * @param $imageType
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:49
     */
    public function customCheck($file, $url, $imageType, array $options = [])
    {
        $options = array_merge(['image_type' => $imageType], $options);
        switch ($imageType) {
            case "BASE64":
                return $this->checkByImage($file, $url, $options);
            case "FACE_TOKEN":
                return $this->checkByToken($file, $url, $options);
            default:
                return $this->checkByUrl($file, $url, $options);
        }
    }

}