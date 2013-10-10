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

class ErrorHandlerBuilder
{
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
    }

    /**
     * @return ErrorHandler
     */
    public function build()
    {
        return new ErrorHandler(
            new Logger(
                $this->getName(),
                $this->getHandlers(),
                $this->getProcessors()
            )
        );
    }

    /**
     * @return ErrorHandler
     */
    public function buildAndRegister()
    {
        return ErrorHandler::register(
            new Logger(
                $this->getName(),
                $this->getHandlers(),
                $this->getProcessors()
            )
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
     * @return ErrorHandlerInterface
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
     * @return ErrorHandlerInterface
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
