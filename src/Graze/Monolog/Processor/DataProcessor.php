<?php

namespace Graze\Monolog\Processor;

use Graze\Monolog\Processor\AbstractKeyValueProcessor;

class DataProcessor extends AbstractKeyValueProcessor
{
    public function __construct($key,$value)
    {
        parent::__construct($key,$value,'data');
    }
}
