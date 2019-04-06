<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Vision;

use EasyAi\Auth;
use EasyAi\Kernel\ServiceContainer;

/**
 * Class Application
 * @package EasyAi\Vision
 *
 * @property  \EasyAi\Auth\AccessToken $access_token
 * @property  \EasyAi\Kernel\Config $config
 * @property  Server\ServerProvider $server
 * @property Face\Client $face
 * @property  Ocr\Client $ocr
 * @property ImageProcess\Client $image_process
 * @property ImageAudit\Client $image_audit
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Auth\ServiceProvider::class,
        Server\ServerProvider::class,
        Ocr\ServiceProvider::class,
        Face\ServiceProvider::class,
        ImageAudit\ServiceProvider::class,
        ImageProcess\ServiceProvider::class
    ];
}