<?php

namespace Graze\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;

abstract class AbstractEventHandler extends AbstractProcessingHandler
{
    /**
     * Event handlers handle all events by default
     *
     */
    public function isHandling(array $record)
    {
        return true;
    }
}
