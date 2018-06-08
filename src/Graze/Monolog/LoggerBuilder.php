<?php
/*
 * This file is part of Monolog Extensions
 *
 * Copyright (c) 2014 Nature Delivered Ltd. <http://graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see  http://github.com/graze/MonologExtensions/blob/master/LICENSE
 * @link http://github.com/graze/MonologExtensions
 */

namespace Graze\Monolog;

use Graze\Monolog\Processor\EnvironmentProcessor;
use Graze\Monolog\Processor\ExceptionMessageProcessor;
use Graze\Monolog\Processor\HttpProcessor;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;

class LoggerBuilder
{
    const DEFAULT_NAME = 'error';

    /**
     * @var HandlerInterface[]
     */
    protected $handlers = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Callable[]
     */
    protected $processors = [];

    /**
     * @return Logger
     */
    public function build()
    {
        return new Logger(
            $this->getName() ?: static::DEFAULT_NAME,
            $this->getHandlers() ?: $this->getDefaultHandlers(),
            $this->getProcessors() ?: $this->getDefaultProcessors()
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return LoggerBuilder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param HandlerInterface $handler
     *
     * @return LoggerBuilder
     */
    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers[] = $handler;

        return $this;
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * @param HandlerInterface[] $handlers
     *
     * @return LoggerBuilder
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = [];

        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }

        return $this;
    }

    /**
     * @param callable $processor
     *
     * @return LoggerBuilder
     */
    public function addProcessor(callable $processor)
    {
        $this->processors[] = $processor;

        return $this;
    }

    /**
     * @return Callable[]
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * @param callable[] $processors
     *
     * @return LoggerBuilder
     */
    public function setProcessors(array $processors)
    {
        $this->processors = [];

        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }

        return $this;
    }

    /**
     * @return HandlerInterface[]
     */
    protected function getDefaultHandlers()
    {
        return [];
    }

    /**
     * @return Callable[]
     */
    protected function getDefaultProcessors()
    {
        return [
            new ExceptionMessageProcessor(),
            new EnvironmentProcessor(),
            new HttpProcessor(),
        ];
    }
}
