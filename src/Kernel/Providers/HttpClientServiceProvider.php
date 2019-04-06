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


use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class HttpClientServiceProvider
 * @package EasyAi\Kernel\Providers
 */
class HttpClientServiceProvider implements ServiceProviderInterface
{
    /**
     * @date 2019/3/28 11:08
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['http_client'] = function ($app) {
            return new Client($app['config']->get('http', []));
        };
    }

}