<?php
namespace Graze\Monolog;

use Graze\Monolog\Event;
use Monolog\TestCase;

class EventTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Event', new Event());
    }

    public function testIdentifier()
    {
        $event = new Event();
        $event->identifier('foo');
        $reflectionClass = new \ReflectionClass($event);
        $nameProperty = $reflectionClass->getProperty('name');
        $nameProperty->setAccessible(true);
        $name = $nameProperty->getValue($event);
        $this->assertEquals('foo', $name);
    }

    public function testData()
    {
        $event = new Event();
        $event->data('foo',5);
        $processor = $event->popProcessor();
        $this->assertInstanceOf('Graze\Monolog\Processor\DataProcessor', $processor);
    }

    public function testMetadata()
    {
        $event = new Event();
        $event->metadata('bar',array());
        $processor = $event->popProcessor();
        $this->assertInstanceOf('Graze\Monolog\Processor\MetadataProcessor', $processor);
    }

    public function testPublish()
    {
        $event = new Event();
        $handler = $this->getMockForAbstractClass('Graze\Monolog\Handler\AbstractEventHandler');
        $event->pushHandler($handler);

        $this->assertTrue($event->publish());
    }

    public function testPublishFailsNoHandler()
    {
        $event = new Event();
        $this->assertFalse($event->publish());
    }
}

