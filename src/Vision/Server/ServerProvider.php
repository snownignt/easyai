<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Vision\Server;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServerProvider
 * @package EasyAi\Vision\Server
 */
class ServerProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @author snownight
     * @date 2019-04-06 22:55
     */
    public function register(Container $app)
    {
        !isset($app['server']) && $app['server'] = function ($app) {
            $guard = new Client($app);
            return $guard;
        };
    }

}