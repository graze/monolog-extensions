<?php
namespace Graze\Monolog\Processor;

use Monolog\TestCase;
use Graze\Monolog\Processor\MetadataProcessor;

class MetadataProcessorTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Processor\MetadataProcessor', new MetadataProcessor('key', 'value'));
    }

    public function testProcessor()
    {
        $processor = new MetadataProcessor('key', 'value');
        $record = $processor($this->getRecord());
        $this->assertEquals('value', $record['metadata']['key']);
    }

    public function testDoesNotSetKeyIfKeyNull()
    {
        $processor = new MetadataProcessor(null, 'value');
        $record = $processor($this->getRecord());
        $this->assertFalse(array_key_exists('key',$record['metadata']));
    }
}

