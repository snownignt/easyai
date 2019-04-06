<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Auth;

use EasyAi\Kernel\AccessToken as BaseAccessToken;

/**
 * Class AccessToken
 * @package EasyAi\Auth
 */
class AccessToken extends BaseAccessToken
{

    /**
     * @var string
     */
    protected $endpointToGetToken = 'oauth/2.0/token';

    /**
     * @date 2019/3/27 15:59
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'grant_type' => isset($this->app['config']['grant_type']) ? $this->app['config']['grant_type'] : 'client_credentials',
            'client_id' => $this->app['config']['app_key'],
            'client_secret' => $this->app['config']['secret']
        ];
    }

}