<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use EasyAi\Kernel\ServiceContainer;
use EasyAi\Kernel\AccessToken;

class TestCase extends BaseTestCase
{
    public function mokApiClient($name, $methods = [], ServiceContainer $app = null)
    {
        $methods = implode(',', array_merge([
            'httpGet', 'httpPost', 'httpPostJson', 'httpUpload',
            'request', 'requestRaw', 'registerMiddlewares'
        ], (array)$methods));

        $client = \Mockery::mock($name . "[{$methods}]", [
            $app ?? \Mockery::mock(ServiceContainer::class),
            \Mockery::mock(AccessToken::class)
        ])->shouldAllowMockingProtectedMethods();
        $client->allows()->registerHttpMiddlewares()->andReturnNull();
        return $client;
    }

    public function tearDown(): void
    {
        $this->finish();
        parent::tearDown();
        if ($container = \Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        \Mockery::close();
    }

    protected function finish()
    {

    }
}