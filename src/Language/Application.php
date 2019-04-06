<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Language;

use EasyAi\Auth;
use EasyAi\Kernel\ServiceContainer;

/**
 * Class Application
 * @property  Auth\AccessToken $access_token
 * @property Text\Client $text
 * @package EasyAi\Language
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Auth\ServiceProvider::class,
        Text\ServiceProvider::class
    ];
}