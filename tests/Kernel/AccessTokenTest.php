<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use EasyAi\Kernel\ServiceContainer;
use EasyAi\Kernel\AccessToken;
use Psr\SimpleCache\CacheInterface;
use EasyAi\Tests\TestCase;

class AccessTokenTest extends TestCase
{
    public function testCache()
    {
        $app = Mockery::mock(ServiceContainer::class)->makePartial();
        $token = Mockery::mock(AccessToken::class)->makePartial();
        $this->assertInstanceOf(CacheInterface::class, $token->getCache());

        $cache = Mockery::mock(CacheInterface::class);
        $app['cache'] = function () use ($cache) {
            return $cache;
        };
        $token = Mockery::mock(AccessToken::class . '[setCache]', [$app]);
        $this->assertInstanceOf(CacheInterface::class, $token->getCache());
    }
}