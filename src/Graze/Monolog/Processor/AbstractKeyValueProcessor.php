<?php

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

        if (!empty($this->key)) {
            $record[$this->location][$this->key] = $this->value;
        }

        return $record;
    }
}
