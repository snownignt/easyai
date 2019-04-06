<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Kernel\Traits;


use EasyAi\Kernel\ServiceContainer;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * Trait InteractsWithCache
 * @package EasyAi\Kernel\Traits
 */
trait InteractsWithCache
{

    /**
     * @var
     */
    protected $cache;


    /**
     * @date 2019/3/27 15:35
     * @return FilesystemCache
     */
    public function getCache()
    {
        if ($this->cache) {
            return $this->cache;
        }
        if (property_exists($this, 'app') && $this->app instanceof ServiceContainer
            && isset($this->app['cache']) && $this->app['cache'] instanceof CacheInterface) {
            return $this->cache = $this->app['cache'];
        }

        return $this->cache = $this->createDefaultCache();
    }

    /**
     * @date 2019/3/27 15:35
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @date 2019/3/27 15:35
     * @return FilesystemCache
     */
    protected function createDefaultCache()
    {
        return new FilesystemCache();
    }

}