<?php

namespace Graze\Monolog\Processor;

use Graze\Monolog\Processor\AbstractKeyValueProcessor;

class MetadataProcessor extends AbstractKeyValueProcessor
{
    public function __construct($key,$value)
    {
        parent::__construct($key,$value,'metadata');
    }
}
