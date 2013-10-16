<?php
/*
 * This file is part of Monolog Extensions
 *
 * Copyright (c) 2013 Nature Delivered Ltd. <http://graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see  http://github.com/graze/MonologExtensions/blob/master/LICENSE
 * @link http://github.com/graze/MonologExtensions
 */
namespace Graze\Monolog;

use Graze\Monolog\Processor\ExceptionMessageProcessor;
use Graze\Monolog\Processor\EnvironmentProcessor;
use Graze\Monolog\Processor\HttpProcessor;
use Monolog\ErrorHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Psr\Log\LogLevel;

class ErrorHandlerBuilder
{
    /**
     * @var array
     */
    protected $errorLevelMap = array();

    /**
     * @var string
     */
    protected $exceptionLevel;

    /**
     * @var string
     */
    protected $fatalLevel;

    /**
     * @var HandlerInterface[]
     */
    protected $handlers;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Callable[]
     */
    protected $processors;

    /**
     * Set defaults
     */
    public function __construct()
    {
        $this->setName('error');
        $this->setHandlers($this->getDefaultHandlers());
        $this->setProcessors($this->getDefaultProcessors());
        $this->setExceptionLevel(LogLevel::CRITICAL);
    }

    /**
     * @return ErrorHandler
     */
    public function build()
    {
        return new ErrorHandler($this->buildLogger());
    }

    /**
     * @return ErrorHandler
     */
    public function buildAndRegister()
    {
        return ErrorHandler::register(
            $this->buildLogger(),
            $this->getErrorLevelMap(),
            $this->getExceptionLevel(),
            $this->getFatalLevel()
        );
    }

    /**
     * @return string
     */
    public function getFatalLevel()
    {
        return $this->fatalLevel;
    }

    /**
     * @param string $level
     * @return ErrorHandlerBuilder
     */
    public function setFatalLevel($level)
    {
        $this->fatalLevel = $level;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrorLevelMap()
    {
        return $this->errorLevelMap;
    }

    /**
     * @param array $map
     * @return ErrorHandlerBuilder
     */
    public function setErrorLevelMap(array $map)
    {
        $this->errorLevelMap = $map;

        return $this;
    }

    /**
     * @return string
     */
    public function getExceptionLevel()
    {
        return $this->exceptionLevel;
    }

    /**
     * @param string $level
     * @return ErrorLevelBuilder
     */
    public function setExceptionLevel($level)
    {
        $this->exceptionLevel = $level;

        return $this;
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
     * @return ErrorHandlerBuilder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param HandlerInterface $handler
     * @return ErrorHandlerBuilder
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
     * @return ErrorHandlerBuilder
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = array();

        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }

        return $this;
    }

    /**
     * @param Callable $processor
     * @return ErrorHandlerBuilder
     */
    public function addProcessor($processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * @return Callable[]
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * @param Callable $processors
     * @return ErrorHandlerBuilder
     */
    public function setProcessors(array $processors)
    {
        $this->processors = array();

        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }

        return $this;
    }

    /**
     * @return Logger
     */
    protected function buildLogger()
    {
        return new Logger(
            $this->getName(),
            $this->getHandlers(),
            $this->getProcessors()
        );
    }

    /**
     * @return HandlerInterface[]
     */
    protected function getDefaultHandlers()
    {
        return array();
    }

    /**
     * @return Callable[]
     */
    protected function getDefaultProcessors()
    {
        return array(
            new ExceptionMessageProcessor(),
            new EnvironmentProcessor(),
            new HttpProcessor()
        );
    }
}
