<?php

namespace Graze\Monolog;

use Monolog\Logger;
use Graze\Monolog\Processor\DataProcessor;
use Graze\Monolog\Processor\MetadataProcessor;
use DateTime;


class Event extends Logger
{
    public function __construct(array $handlers = array(), array $processors = array())
    {
        parent::__construct('defaultEvent', $handlers, $processors);
        return $this;
    }

    public function data($key,$value)
    {
        $this->pushProcessor(new DataProcessor($key,$value));
        return $this;
    }

    public function metadata($key,$value)
    {
        $this->pushProcessor(new MetadataProcessor($key,$value));
        return $this;
    }

    public function identifier($name)
    {
        $this->name = $name;
        return $this;
    }

    public function publish()
    {
        if (!static::$timezone) {
            static::$timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');
        }

        $event = array(
            'eventIdentifier' => (string) $this->name,
            'timestamp' => DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), static::$timezone)->setTimezone(static::$timezone),
            'data' => array(),
            'metadata' => array(),
        );

        // check if any handler will handle this message
        $handlerKey = null;
        foreach ($this->handlers as $key => $handler) {
            if ($handler->isHandling($event)) {
                $handlerKey = $key;
                break;
            }
        }
        // none found
        if (null === $handlerKey) {
            return false;
        }

        // found at least one, process message and dispatch it
        foreach ($this->processors as $processor) {
            $event = call_user_func($processor, $event);
        }
        while (isset($this->handlers[$handlerKey]) &&
            false === $this->handlers[$handlerKey]->handle($event)) {
            $handlerKey++;
        }

        return true;
    }
}
