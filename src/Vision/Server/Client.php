<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Vision\Server;


use EasyAi\Kernel\Exceptions\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use EasyAi\Kernel\ServiceContainer;
use EasyAi\Kernel\Traits\ResponseCastable;

/**
 * Class Client
 * @package EasyAi\Vision\Server
 */
class Client
{
    use ResponseCastable;

    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * Client constructor.
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * @date 2019/3/27 17:36
     * @return Response
     * @throws BadRequestException
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     */
    public function serve(): Response
    {
        $response = $this->resolve();

        return $response;
    }

    /**
     * @date 2019/3/27 17:36
     * @return Response
     * @throws BadRequestException
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     */
    public function resolve(): Response
    {
        return $this->handleRequest();

    }

    /**
     * @date 2019/3/27 17:36
     * @return mixed
     * @throws BadRequestException
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     */
    public function handleRequest()
    {
        $castedMessage = $this->getMessage();

        $response = $this->detectAndCastResponseToType($castedMessage, 'array');
        return new Response($response);
    }

    /**
     * @date 2019/3/27 17:36
     * @return mixed
     * @throws BadRequestException
     * @throws \EasyAi\Kernel\Exceptions\InvalidArgumentException
     */
    public function getMessage()
    {
        $message = $this->parseMessage($this->app['request']->getContent(false));
        if (!is_array($message) || empty($message)) {
            throw new  BadRequestException("no message received");
        }
        return $this->detectAndCastResponseToType($message, $this->app->config->get('response_type'));
    }

    /**
     * @date 2019/3/27 17:36
     * @param $content
     * @return array
     */
    protected function parseMessage($content)
    {
        $dataSet = json_decode($content, true);
        if ($dataSet && (JSON_ERROR_NONE === json_last_error())) {
            $content = $dataSet;
        }
        return (array)$content;
    }

    /**
     * @author snownight
     * @date 2019/4/1 14:22
     * @return mixed
     */
    protected function getToken()
    {
        return $this->app['config']['token'];
    }

}


















