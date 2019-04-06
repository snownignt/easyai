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


use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider
 * @package EasyAi\Language\Text
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @author snownight
     * @date 2019-04-06 22:54
     */
    public function register(Container $app)
    {
        $app['text'] = function ($app) {
            return new Client($app);
        };
    }

}