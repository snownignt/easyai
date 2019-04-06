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


use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasyAi\Auth
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @date 2019/3/27 15:58
     * @param Container $app
     */
    public function register(Container $app)
    {
        !isset($app['access_token']) && $app['access_token'] = function ($app) {
            return new AccessToken($app);
        };
    }

}