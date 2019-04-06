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


use Psr\Http\Message\RequestInterface;

/**
 * Interface AccessTokenInterface
 * @package EasyAi\Kernel\Contracts
 */
interface AccessTokenInterface
{

    /**
     * @date 2019/3/27 11:41
     * @return array
     */
    public function getToken(): array;

    /**
     * @date 2019/3/27 11:41
     * @return AccessTokenInterface
     */
    public function refresh(): self;

    /**
     * @date 2019/3/27 11:41
     * @param RequestInterface $request
     * @param array $requestOption
     * @return RequestInterface
     */
    public function applyToRequest(RequestInterface $request, array $requestOption = []): RequestInterface;

}