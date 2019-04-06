<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi;


use EasyAi\Kernel\Support\Str;

/**
 * @method static Vision\Application vision(array $config)
 * @method static Language\Application language($config)
 * Class Factory
 * @package EasyAi
 */
class Factory
{
    /**
     * @date 2019/3/26 10:47
     * @param string $name service name
     * @param array $config
     * @return Kernel\ServiceContainer
     */
    static function make($name, array $config)
    {
        $namespace = Str::studly($name);
        $application = "\\EasyAi\\{$namespace}\\Application";
        return new $application($config);
    }


    /**
     * @date 2019/3/26 10:44
     * @param $name
     * @param $argument
     * @return mixed
     */
    public static function __callStatic($name, $argument)
    {
        return self::make($name, ...$argument);
    }
}