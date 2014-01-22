<?php
namespace Graze\Monolog\Processor;

use Monolog\TestCase;
use Graze\Monolog\Processor\AbstractKeyValueProcessor;

class AbstractKeyValueProcessorTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Processor\AbstractKeyValueProcessor', $this->getMockForAbstractClass('Graze\Monolog\Processor\AbstractKeyValueProcessor', array('key', 'value', 'location')));
    }

    public function testProcessor()
    {
        $processor = $this->getMockForAbstractClass('Graze\Monolog\Processor\AbstractKeyValueProcessor', array('key', 'value', 'location'));
        $record = $processor($this->getRecord());
        $this->assertEquals('value', $record['location']['key']);
    }

    public function testDoesNotSetKeyIfKeyNull()
    {
        $processor = $this->getMockForAbstractClass('Graze\Monolog\Processor\AbstractKeyValueProcessor', array(null, 'value', 'location'));
        $record = $processor($this->getRecord());
        $this->assertFalse(array_key_exists('key',$record['location']));
    }
}

