<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Kernel\Log;


use EasyAi\Kernel\Exceptions\InvalidArgumentException;
use EasyAi\Kernel\ServiceContainer;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger as MonoLog;
use Psr\Log\LoggerInterface;

/**
 * Class LogManager
 * @package EasyAi\Kernel\Log
 */
class LogManager implements LoggerInterface
{

    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * @var array
     */
    protected $channels = [];

    /**
     * @var
     */
    protected $customCreators;

    /**
     * @var array
     */
    protected $levels = [
        'debug' => Monolog::DEBUG,
        'info' => MonoLog::INFO,
        'notice' => MonoLog::NOTICE,
        'warning' => MonoLog::WARNING,
        'error' => MonoLog::ERROR,
        'critical' => MonoLog::CRITICAL,
        'alert' => MonoLog::ALERT,
        'emergency' => MonoLog::EMERGENCY
    ];

    /**
     * LogManager constructor.
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * author: lichengjun
     * date: 2019-04-06 15:47
     * @param array $channels
     * @param $channel
     * @return MonoLog
     * @throws InvalidArgumentException
     */
    public function stack(array $channels, $channel)
    {
        return $this->createStackDriver(compact('channel', 'channels'));
    }


    /**
     * @date 2019/3/28 17:22
     * @param null $channel
     * @return mixed|MonoLog
     * @throws InvalidArgumentException
     */
    public function channel($channel = null)
    {
        return $this->get($channel);
    }

    /**
     * @date 2019/3/28 17:22
     * @param null $driver
     * @return mixed|MonoLog
     * @throws InvalidArgumentException
     */
    public function driver($driver = null)
    {
        return $this->get($driver ?? $this->getDefaultDriver());
    }

    /**
     * @date 2019/3/28 17:32
     * @param $name
     * @return LoggerInterface
     * @throws InvalidArgumentException
     */
    public function get($name)
    {
        try {
            return $this->channels[$name] ?? ($this->channels[$name] = $this->resolve($name));
        } catch (\Throwable $e) {
            $logger = $this->createEmergencyLogger();
            $logger->emergency('Unable to create configured logger. Using emergency logger', [
                'exception' => $e
            ]);
            return $logger;
        }
    }


    /**
     * @date 2019/3/28 17:20
     * @param $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function resolve($name)
    {
        $config = $this->app['config']->get(sprintf("log.channels.%s", $name));
        if (is_null($config)) {
            throw new InvalidArgumentException('Log [%s] us not defined.', $name);
        }

        if (isset($this->customCreators[$config['drive']])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create' . ucfirst($config['driver']) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}{$config};
        }

        throw new InvalidArgumentException("Driver [%s] is not supported.", $config['driver']);
    }


    /**
     * @date 2019/3/28 17:23
     * @return MonoLog
     * @throws InvalidArgumentException|\Exception
     */
    protected function createEmergencyLogger()
    {
        return new MonoLog('EasyAi', $this->prepareHandlers([new StreamHandler(
            sys_get_temp_dir() . 'easyai/easy-ai.log'), $this->level(['level' => 'debug']
        )]));
    }

    /**
     * @date 2019/3/28 17:28
     * @param array $config
     * @return mixed
     */
    protected function callCustomCreator(array $config)
    {
        return $this->customCreators[$config['driver']]($this->app, $config);
    }

    /**
     * @date 2019/3/28 17:28
     * @param array $config
     * @return MonoLog
     * @throws InvalidArgumentException
     */
    protected function createStackDriver(array $config)
    {
        $handlers = [];
        foreach ($config['channels'] ?? [] as $channel) {
            $handlers = array_merge($handlers, $this->channel($channel)->getHandlers());
        }

        return new MonoLog($this->parseChannel($config), $handlers);
    }


    /**
     * @param array $config
     * @return MonoLog
     * @throws InvalidArgumentException|\Exception
     * @author lichengjun
     * @date 2019-04-06 15:48
     */
    public function createSingleDriver(array $config)
    {
        return new MonoLog($this->parseChannel($config), [
            $this->prepareHandler(
                new StreamHandler($config['path'], $this->level($config))
            )
        ]);
    }

    /**
     * @date 2019/3/28 17:19
     * @param array $config
     * @return MonoLog
     * @throws InvalidArgumentException
     */
    public function createDailyDriver(array $config)
    {
        return new MonoLog($this->parseChannel($config), [
            $this->prepareHandler(new RotatingFileHandler(
                $config['path'], $config['days'] ?? 7, $this->level($config)
            ))
        ]);
    }

    /**
     * @date 2019/3/28 17:21
     * @param array $config
     * @return MonoLog
     * @throws InvalidArgumentException
     */
    public function createSlackDriver(array $config)
    {
        return new MonoLog($this->parseChannel($config), [
            $this->prepareHandler(new SlackWebhookHandler(
                $config['url'],
                $config['channel'] ?? null,
                $config['username'] ?? 'EasyAi',
                $config['attachment'] ?? true,
                $config['emoji'] ?? ":boom",
                $config['short'] ?? false,
                $config['context'] ?? true,
                $this->level($config)
            ))
        ]);
    }


    /**
     * @date 2019/3/28 17:22
     * @param array $config
     * @return MonoLog
     * @throws InvalidArgumentException
     */
    protected function createSyslogDriver(array $config)
    {
        return new MonoLog($this->parseChannel($config), [
            $this->prepareHandler(new SyslogHandler(
                'EasyAi', $config['facility'] ?? LOG_USER, $this->level($config)
            ))
        ]);
    }


    /**
     * @date 2019/3/28 17:19
     * @param array $config
     * @return MonoLog
     * @throws InvalidArgumentException
     */
    protected function createErrorlogDriver(array $config)
    {
        return new MonoLog($this->parseChannel($config), [
            $this->prepareHandler(new ErrorLogHandler(
                $config['type'] ?? ErrorLogHandler::OPERATING_SYSTEM, $this->level($config)
            ))
        ]);
    }

    /**
     * @date 2019/3/28 17:28
     * @param array $handlers
     */
    protected function prepareHandlers(array $handlers)
    {
        foreach ($handlers as $key => $handler) {
            $handlers[$key] = $this->prepareHandler($handler);
        }
    }

    /**
     * @date 2019/3/28 17:28
     * @param HandlerInterface $handler
     * @return HandlerInterface
     */
    protected function prepareHandler(HandlerInterface $handler)
    {
        return $handler->setFormatter($this->formatter());
    }

    /**
     * @date 2019/3/28 17:28
     * @return LineFormatter
     */
    public function formatter()
    {
        $formatter = new LineFormatter(null, null, true, true);
        $formatter->includeStacktraces();
        return $formatter;
    }

    /**
     * @date 2019/3/28 17:28
     * @param array $config
     * @return mixed|null
     */
    public function parseChannel(array $config)
    {
        return $config['name'] ?? null;
    }


    /**
     * @date 2019/3/28 17:19
     * @param array $config
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function level(array $config)
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }
        throw new InvalidArgumentException("Invalid log level");
    }

    /**
     * @date 2019/3/28 17:26
     * @return mixed
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['log.default'];
    }

    /**
     * @date 2019/3/28 17:26
     * @param $name
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['log.default'] = $name;
    }

    /**
     * @date 2019/3/28 17:26
     * @param $driver
     * @param \Closure $callback
     * @return $this
     */
    public function extend($driver, \Closure $callback)
    {
        $this->customCreators[$driver] = $callback->bindTo($this, $this);
        return $this;
    }


    /**
     * @date 2019/3/28 17:33
     * @param string $message
     * @param array $context
     * @return bool|void
     * @throws InvalidArgumentException
     */
    public function emergency($message, array $context = [])
    {
        return $this->driver()->emergency($message, $context);
    }

    /**
     * @date 2019/3/28 17:26
     * @param string $message
     * @param array $context
     * @return bool|void
     * @throws InvalidArgumentException
     */
    public function alert($message, array $context = [])
    {
        return $this->driver()->alert($message, $context);
    }


    /**
     * @date 2019/3/28 17:26
     * @param string $message
     * @param array $context
     * @return bool|void
     * @throws InvalidArgumentException
     */
    public function critical($message, array $context = [])
    {
        return $this->driver()->critical($message, $context);
    }

    /**
     * @date 2019/3/28 17:26
     * @param string $message
     * @param array $context
     * @return bool|void
     * @throws InvalidArgumentException
     */
    public function error($message, array $context = [])
    {
        return $this->driver()->error($message, $context);
    }

    /**
     * @date 2019/3/28 17:26
     * @param string $message
     * @param array $context
     * @return bool|void
     * @throws InvalidArgumentException
     */
    public function warning($message, array $context = [])
    {
        return $this->driver()->warning($message, $context);
    }

    /**
     * @date 2019/3/28 17:26
     * @param string $message
     * @param array $context
     * @return bool|void
     * @throws InvalidArgumentException
     */
    public function notice($message, array $context = [])
    {
        return $this->driver()->notice($message, $context);
    }

    /**
     * @date 2019/3/28 17:26
     * @param string $message
     * @param array $context
     * @return bool|void
     * @throws InvalidArgumentException
     */
    public function info($message, array $context = [])
    {
        return $this->driver()->info($message, $context);
    }

    /**
     * @date 2019/3/28 17:26
     * @param string $message
     * @param array $context
     * @return bool|void
     * @throws InvalidArgumentException
     */
    public function debug($message, array $context = [])
    {
        return $this->driver()->debug($message, $context);
    }

    /**
     * @date 2019/3/28 17:26
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return bool|void
     * @throws InvalidArgumentException
     */
    public function log($level, $message, array $context = [])
    {
        return $this->driver()->log($level, $message, $context);
    }

    /**
     * @date 2019/3/28 17:26
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }

}
























