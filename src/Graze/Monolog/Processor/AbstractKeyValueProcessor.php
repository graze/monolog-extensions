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
namespace Graze\Monolog\Processor;

abstract class AbstractKeyValueProcessor
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $location;

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $location
     */
    public function __construct($key,$value,$location)
    {
        $this->key = $key;
        $this->value = $value;
        $this->location = $location;
    }

    public function __invoke(array $record)
    {
        if (!array_key_exists($this->location, $record)) {
            $record[$this->location] = array();
        }

        if (null !== $this->key) {
            $record[$this->location][$this->key] = $this->value;
        }

        return $record;
    }
}
