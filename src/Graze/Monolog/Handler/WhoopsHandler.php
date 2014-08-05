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
namespace Graze\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Whoops\Handler\Handler;
use Whoops\Handler\HandlerInterface as WhoopsHandlerInterface;
use Whoops\Exception\ErrorException as WhoopsErrorException;
use Whoops\Exception\Inspector as WhoopsInspector;
use Whoops\Run as WhoopsRun;

/**
 * Allow the use of Whoops\Handler\HandlerInterface handlers with Monolog
 *
 * @author John
 */
class WhoopsHandler extends AbstractProcessingHandler
{
    /**
     * @var WhoopsHandlerInterface
     */
    protected $whoopsHandler;

    /**
     * @param WhoopsHandlerInterface $whoopsHandler
     * @param integer $level
     * @param boolean $bubble
     */
    public function __construct(WhoopsHandlerInterface $whoopsHandler, $level = Logger::DEBUG, $bubble = true)
    {
        $this->whoopsHandler = $whoopsHandler;

        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     */
    protected function write(array $record)
    {
        $context = $record['context'];

        if (isset($context['exception']) && $context['exception'] instanceof \Exception) {
            $this->writeException($context['exception']);
        } elseif (isset($context['file']) && $context['line']) {
            $this->writeError($record);
        }
    }

    /**
     * Whoops only deals with Exceptions. Create a WhoopsErrorException based on the error details and handle that
     *
     * @param array $record
     */
    protected function writeError(array $record)
    {
        $exception = new WhoopsErrorException(
            $record['message'],
            $record['level'],
            0,
            $record['context']['file'],
            $record['context']['line']
        );
        
        $this->writeException($exception);
    }

    /**
     * @param Exception $exception
     */
    protected function writeException(\Exception $exception)
    {
        $whoopsInspector = new WhoopsInspector($exception);
        
        $this->whoopsHandler->setInspector($whoopsInspector);
        $this->whoopsHandler->setException($exception);
        $this->whoopsHandler->setRun(new WhoopsRun);
        
        $whoopsHandleResponse = $this->whoopsHandler->handle();
        
        $this->processWhoopsBubbling($whoopsHandleResponse);
    }
    
    /**
     * Map Whoops->handle() responses to Monolog bubbling
     *
     * @param int $whoopsHandleResponse response as returned from Whoops\Handler\Handler::handle()
     */
    protected function processWhoopsBubbling($whoopsHandleResponse)
    {
        switch ($whoopsHandleResponse) {
            case Handler::LAST_HANDLER:
            case Handler::QUIT:
                // don't call further monolog handlers
                $this->setBubble(false);
        }
    }
}
