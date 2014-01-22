<?php
namespace Graze\Monolog\Processor;

use Monolog\TestCase;
use Graze\Monolog\Processor\DataProcessor;

class DataProcessorTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Processor\DataProcessor', new DataProcessor('key', 'value'));
    }

    public function testProcessor()
    {
        $processor = new DataProcessor('key', 'value');
        $record = $processor($this->getRecord());
        $this->assertEquals('value', $record['data']['key']);
    }

    public function testDoesNotSetKeyIfKeyNull()
    {
        $processor = new DataProcessor(null, 'value');
        $record = $processor($this->getRecord());
        $this->assertFalse(array_key_exists('key',$record['data']));
    }
}

