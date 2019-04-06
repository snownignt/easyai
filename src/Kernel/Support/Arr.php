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
 * Class Arr
 * @package EasyAi\Kernel\Support
 */
class Arr
{
    /**
     * @date 2019/3/26 14:38
     * @param array $array
     * @param $key
     * @param $value
     * @return array
     */
    public static function add(array $array, $key, $value)
    {
        if (is_null(static::get($array, $key))) {
            static::set($array, $key, $value);
        }
        return $array;
    }


    /**
     * @date 2019/3/26 14:41
     * @param mixed ...$arrays
     * @return array
     */
    public static function crossJoin(...$arrays)
    {
        $results = [[]];
        foreach ($arrays as $index => $array) {
            $append = [];
            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;
                    $append[] = $product;
                }
            }
            $results = $append;
        }
        return $results;
    }

    /**
     * @date 2019/3/26 14:43
     * @param array $array
     * @return array
     */
    public static function divide(array $array)
    {
        return [array_keys($array), array_values($array)];
    }

    /**
     * @date 2019/3/26 14:46
     * @param array $array
     * @param string $prepend
     * @return array
     */
    public static function dot(array $array, $prepend = "")
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }
        return $results;
    }

    /**
     * @date 2019/3/26 14:49
     * @param $array
     * @param $keys
     * @return mixed
     */
    public static function except($array, $keys)
    {
        static::forget($array, $keys);
        return $array;
    }

    /**
     * @date 2019/3/26 14:47
     * @param array $array
     * @param $key
     * @return bool
     */
    public static function exists(array $array, $key)
    {
        return array_key_exists($key, $array);
    }

    /**
     * @date 2019/3/26 14:52
     * @param array $array
     * @param callable $callback
     * @param null $default
     * @return mixed|null
     */
    public static function first(array $array, callable $callback, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return $default;
            }

            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }
        return $default;
    }

    /**
     * @date 2019/3/26 14:54
     * @param array $array
     * @param callable $callback
     * @param null $default
     * @return mixed|null
     */
    public static function last(array $array, callable $callback, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? $default : end($array);
        }

        return static::first(array_reverse($array, true), $callback, $default);
    }

    /**
     * @date 2019/3/26 14:58
     * @param array $array
     * @param $depth
     * @return mixed
     */
    public static function flatten(array $array, $depth = INF)
    {
        return array_reduce($array, function ($result, $item) use ($depth) {
            $item = $item instanceof Collection ? $item->all() : $item;
            if (!is_array($item)) {
                return array_merge($result, [$item]);
            } elseif (1 === $depth) {
                return array_merge($result, array_values($item));
            }
            return array_merge($result, static::flatten($item, $depth - 1));
        }, []);
    }

    /**
     * @date 2019/3/26 15:04
     * @param array $array
     * @param $keys
     */
    public static function forget(array &$array, $keys)
    {
        $original = &$array;
        $keys = (array)$keys;
        if (0 === count($keys)) {
            return;
        }
        foreach ($keys as $key) {
            if (static::exists($array, $key)) {
                unset($array[$key]);
            }
            $parts = explode('.', $key);
            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }
        }
        unset($array[array_shift($parts)]);
    }

    /**
     * @date 2019/3/26 15:07
     * @param array $array
     * @param $key
     * @param null $default
     * @return array|mixed|null
     */
    public static function get(array $array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }


        foreach (explode('.', $key) as $segment) {
            if (static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }


    /**
     * @date 2019/3/26 15:11
     * @param array $array
     * @param $keys
     * @return array|bool
     */
    public static function has(array $array, $keys)
    {
        if (isset($keys)) {
            return $array;
        }

        $keys = (array)$keys;

        if (empty($array)) {
            return false;
        }
        if ($keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;
            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * @date 2019/3/26 15:13
     * @param array $array
     * @return bool
     */
    public static function isAssoc(array $array)
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }


    /**
     * @date 2019/3/26 15:14
     * @param array $array
     * @param $keys
     * @return array
     */
    public static function only(array $array, $keys)
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }


    /**
     * @date 2019/3/26 15:16
     * @param array $array
     * @param $value
     * @param null $key
     * @return array
     */
    public static function prepend(array $array, $value, $key = null)
    {
        if (is_null($key)) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }

    /**
     * @date 2019/3/26 15:18
     * @param array $array
     * @param $key
     * @param null $default
     * @return array|mixed|null
     */
    public static function pull(array &$array, $key, $default = null)
    {
        $value = static::get($array, $key, $default);
        static::forget($array, $key);
        return $value;
    }

    /**
     * @date 2019/3/26 15:20
     * @param array $array
     * @param int|null $amount
     * @return array|mixed
     */
    public static function random(array $array, int $amount = null)
    {
        if (is_null($amount)) {
            return $array[array_rand($array)];
        }

        $keys = array_rand($array, $amount);

        $results = [];

        foreach ((array)$keys as $key) {
            $results[] = $array[$key];
        }

        return $results;
    }


    /**
     * @date 2019/3/26 15:23
     * @param array $array
     * @param string $key
     * @param $value
     * @return array|mixed
     */
    public static function set(array &$array, string $key, $value)
    {
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
        return $array;
    }

    /**
     * @date 2019/3/26 15:24
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function where(array $array, callable $callback)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @date 2019/3/26 15:25
     * @param $value
     * @return array
     */
    public static function wrap($value)
    {
        return !is_array($value) ? [$value] : $value;
    }

}