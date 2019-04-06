<?php
/*
 * This file is part of the easyai package.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EasyAi\Language\Text;


use EasyAi\Kernel\BaseClient;

/**
 * Class Client
 * @package EasyAi\Language\Text
 */
class Client extends BaseClient
{
    /**
     * @author snownight
     * @date 2019-04-01 22:31
     * @param string $content
     * @return array|\EasyAi\Kernel\Http\Response|\EasyAi\Kernel\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function spam(string $content)
    {
        return $this->httpPost('rest/2.0/antispam/v2/spam', ['content' => $content]);
    }
}