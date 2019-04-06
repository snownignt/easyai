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


use EasyAi\Kernel\Contracts\ArrayAble;

/**
 * Class Collection
 * @package EasyAi\Kernel\Support
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable, \Serializable, ArrayAble
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @date 2019/3/26 14:32
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @date 2019/3/26 14:34
     * @param array $keys
     * @return Collection
     */
    public function only(array $keys)
    {
        $return = [];

        foreach ($keys as $key) {
            $value = $this->get($key);
            if (!is_null($value)) {
                $return[$key] = $value;
            }
        }
        return new static($return);
    }

    /**
     * @date 2019/3/26 14:36
     * @param $keys
     * @return Collection
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        return new static(Arr::except($this->items, $keys));
    }

    /**
     * @date 2019/3/26 15:29
     * @param $items
     * @return Collection
     */
    public function merge($items)
    {
        $clone = new static($this->all());

        foreach ($items as $key => $value) {
            $clone->set($key, $value);
        }

        return $clone;
    }

    /**
     * @date 2019/3/26 15:30
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return !is_null(Arr::get($this->items, $key));
    }

    /**
     * @date 2019/3/26 15:31
     * @return mixed
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * @date 2019/3/26 15:32
     * @return mixed
     */
    public function last()
    {
        $end = end($this->items);
        reset($this->items);
        return $end;
    }


    /**
     * @date 2019/3/26 15:32
     * @param $key
     * @param $value
     */
    public function add($key, $value)
    {
        Arr::set($this->items, $key, $value);
    }

    /**
     * @date 2019/3/26 15:46
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        Arr::set($this->items, $key, $value);
    }

    /**
     * @date 2019/3/26 15:34
     * @param $key
     * @param null $default
     * @return array|mixed|null
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * @date 2019/3/26 15:34
     * @param $key
     */
    public function forget($key)
    {
        Arr::forget($this->items, $key);
    }

    /**
     * @date 2019/3/26 15:35
     * @return array
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * @date 2019/3/26 15:36
     * @param int $option
     * @return false|string
     */
    public function toJson($option = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->all(), $option);
    }

    /**
     * @date 2019/3/26 15:36
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }


    /**
     * @date 2019/3/26 15:38
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->items;
    }

    /**
     * @date 2019/3/26 15:46
     * @return string
     */
    public function serialize()
    {
        return serialize($this->items);
    }

    /**
     * @date 2019/3/26 15:46
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @date 2019/3/26 15:46
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @date 2019/3/26 15:52
     * @param string $serialized
     * @return mixed|void
     */
    public function unserialize($serialized)
    {
        return $this->items = unserialize($serialized);
    }

    /**
     * @date 2019/3/26 15:46
     * @param $name
     * @return array|mixed|null
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @date 2019/3/26 15:46
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @date 2019/3/26 15:46
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * @date 2019/3/26 15:47
     * @param $name
     */
    public function __unset($name)
    {
        $this->forget($name);
    }

    /**
     * @date 2019/3/26 15:47
     * @return array
     */
    public function __set_state()
    {
        return $this->all();
    }

    /**
     * @date 2019/3/26 15:47
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @date 2019/3/26 15:47
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->forget($offset);
        }
    }

    /**
     * @date 2019/3/26 15:47
     * @param mixed $offset
     * @return array|mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->get($offset) : null;
    }

    /**
     * @date 2019/3/26 15:47
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }


}