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

use Monolog\ErrorHandler;
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
    protected $exceptionLevel = LogLevel::CRITICAL;

    /**
     * @var string
     */
    protected $fatalLevel;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @return ErrorHandler
     */
    public function build()
    {
        return new ErrorHandler($this->getLogger() ?: $this->getDefaultLogger());
    }

    /**
     * @return ErrorHandler
     */
    public function buildAndRegister()
    {
        return ErrorHandler::register(
            $this->getLogger() ?: $this->getDefaultLogger(),
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
     * @return ErrorHandlerBuilder
     */
    public function setExceptionLevel($level)
    {
        $this->exceptionLevel = $level;

        return $this;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     * @return ErrorHandlerBuilder
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return Logger
     */
    protected function getDefaultLogger()
    {
        $builder = new LoggerBuilder();

        return $builder->build();
    }
}
