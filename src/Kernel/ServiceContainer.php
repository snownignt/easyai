<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Kernel;


use EasyAi\Kernel\Providers\ConfigServiceProvider;
use EasyAi\Kernel\Providers\HttpClientServiceProvider;
use EasyAi\Kernel\Providers\LogServiceProvider;
use EasyAi\Kernel\Providers\RequestServiceProvider;
use Pimple\Container;

/**
 * @method static \EasyAi\Kernel\Config  $config
 * Class ServiceContainer
 * @package EasyAi\Kernel
 */
class ServiceContainer extends Container
{
    /**
     * @var
     */
    protected $id;

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * ServiceContainer constructor.
     * @param array $config
     * @param string|null $id
     */
    public function __construct(array $config = array(), string $id = null)
    {
        $this->registerProvider($this->getProviders());
        parent::__construct($config);
        $this->userConfig = $config;
        $this->id = $id;
    }

    /**
     * @date 2019/3/26 14:14
     * @return string
     */
    public function getId()
    {
        return $this->id ?? md5(json_encode($this->userConfig));
    }

    /**
     * @date 2019/3/26 14:18
     * @return array
     */
    public function getConfig()
    {
        $base = [
            "http" => [
                "timeout" => 30.0,
                "base_uri" => "https://aip.baidubce.com"
            ]
        ];
        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    /**
     * @date 2019/3/26 14:19
     * @return array
     */
    public function getProviders()
    {
        return array_merge([
            ConfigServiceProvider::class,
            RequestServiceProvider::class,
            HttpClientServiceProvider::class,
            LogServiceProvider::class
        ], $this->providers);
    }

    /**
     * @date 2019/3/26 14:20
     * @param $id
     * @param $value
     */
    public function rebind($id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
    }

    /**
     * @date 2019/4/1 14:35
     * @param $id
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * @date 2019/3/26 14:25
     * @param $providers
     */
    public function registerProvider($providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}