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


use EasyAi\Kernel\Log\LogManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class LogServiceProvider
 * @package EasyAi\Kernel\Providers
 */
class LogServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     * @date 2019-04-06 22:49
     */
    public function register(Container $pimple)
    {
        $pimple['logger'] = $pimple['log'] = function ($app) {
            $config = $this->formatlogConfig($app);

            if (!empty($config)) {
                $app->rebind('config', $app['config']->merge($config));
            }

            return new LogManager($app);
        };
    }


    /**
     * @param $app
     * @return array
     * @date 2019-04-06 22:49
     */
    public function formatLogConfig($app)
    {
        if (!empty($app['config']->get('log.channels'))) {
            return $app['con']->get('log');
        }

        if (empty($app['config']->get('log'))) {
            return [
                'log' => [
                    'default' => 'errorlog',
                    'channels' => [
                        'errorlog' => [
                            'driver' => 'errorlog',
                            'level' => 'debug'
                        ]
                    ]
                ]
            ];
        }

        return [
            'log' => [
                'default' => 'single',
                'channels' => [
                    'single' => [
                        'driver' => 'single',
                        'path' => $app['config']->get('log.file') ?: \sys_get_temp_dir() . '/logs/easy-ai.log',
                        'level' => $app['config']->get('log.level', 'debug')
                    ]
                ]
            ]
        ];
    }
}