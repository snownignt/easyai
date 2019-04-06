<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Kernel\Providers;


use EasyAi\Kernel\Config;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ConfigServiceProvider
 * @package EasyAi\Kernel\Providers
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @date 2019/3/27 18:48
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['config'] = function ($app) {
            return new Config($app->getConfig());
        };
    }
}