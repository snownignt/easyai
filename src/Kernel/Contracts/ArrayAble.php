<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Kernel\Contracts;

use ArrayAccess;

/**
 * Interface ArrayAble
 * @package EasyAi\Kernel\Contracts
 */
interface ArrayAble extends ArrayAccess
{
    /**
     * @date 2019/3/26 15:51
     * @return mixed
     */
    public function toArray();
}