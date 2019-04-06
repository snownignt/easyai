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


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestServiceProvider
 * @package EasyAi\Kernel\Providers
 */
class RequestServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     * @author snownight
     * @date 2019-04-06 22:50
     */
    public function register(Container $pimple)
    {
        $pimple['request'] = function () {
            return Request::createFromGlobals();
        };
    }

}