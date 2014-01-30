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

use DateTime;
use DateTimeZone;

class Event
{
    /**
     * @var array
     */
    private $eventData;

    /**
     * @var array of event handlers
     */
    private $handlers;

    /**
     * @var \DateTimeZone
     */
    protected static $timezone;

    /**
     * @param array $handlers
     * @return $this
     */
    public function __construct(array $handlers = array())
    {
        $this->handlers = $handlers;
        $this->eventData = array(
            'eventIdentifier' => 'defaultEvent',
            'timestamp'       => $this->getNow(),
            'data'            => array(),
            'metadata'        => array(),

        );

        return $this;
    }

    /**
     * sets $value under key $key in data store
     *
     * @param  string $key
     * @param  mixed $value
     * @return $this
     */
    public function setData($key, $value)
    {
        $this->eventData['data'][$key] = $value;
        return $this;
    }

    /**
     * sets $value under key $key in metadata store
     *
     * @param  string $key
     * @param  mixed $value
     * @return $this
     */
    public function setMetadata($key, $value)
    {
        $this->eventData['metadata'][$key] = $value;
        return $this;
    }

    /**
     * sets the (string) identifier of the event by which it will be identified
     *
     * @param  string $identifier
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->eventData['eventIdentifier'] = $identifier;
        return $this;
    }

    /**
     * triggers all the event handlers set for this event
     *
     * @return  boolean true if at least one handler set
     */
    public function publish()
    {
        if (empty($this->handlers)) {
            return false;
        }

        foreach ($this->handlers as $handler) {
            $handler->handle($this->eventData);
        }
        return true;
    }

    /**
     * returns a datetime object representing the current instant with microseconds
     *
     * @return \DateTime
     */
    private function getNow()
    {
        if (!static::$timezone) {
            static::$timezone = new DateTimeZone(date_default_timezone_get() ?: 'UTC');
        }

        return DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), static::$timezone)->setTimezone(static::$timezone);
    }
}
