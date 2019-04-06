<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Kernel\Support;


/**
 * Class Str
 * @package EasyAi\Kernel\Support
 */
class Str
{
    /**
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * namespace transform
     * @date 2019/3/26 11:35
     * @param $value
     * @return mixed
     */
    public static function studly($value)
    {
        $key = $value;
        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }
        $value = ucwords(str_replace(["-", "_"], " ", $value));
        return static::$studlyCache[$key] = str_replace(" ", "", $value);
    }
}