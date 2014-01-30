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

    public function setupHandlerWithValidationCallback($callback)
    {
        $handler = $this->getMock('Monolog\Handler\HandlerInterface');
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->callback($callback));

        return $handler;
    }

    public function testIdentifier()
    {
        $callback = function($data) {
            return 'foo' === $data['eventIdentifier'];
        };
        $handler = $this->setupHandlerWithValidationCallback($callback);

        $event = new Event(array($handler));
        $event->setIdentifier('foo');
        $event->publish();
    }

    public function testData()
    {
        $callback = function($data) {
            return 5 === $data['data']['foo'];
        };
        $handler = $this->setupHandlerWithValidationCallback($callback);

        $event = new Event(array($handler));
        $event->setData('foo',5);
        $event->publish();
    }

    public function testMetadata()
    {
        $callback = function($data) {
            return array() === $data['metadata']['bar'];
        };
        $handler = $this->setupHandlerWithValidationCallback($callback);

        $event = new Event(array($handler));
        $event->setMetadata('bar',array());
        $event->publish();
    }

    public function testPublish()
    {
        $handler = $this->getMock('Monolog\Handler\HandlerInterface');
        $handler->expects($this->once())
                ->method('handle');

        $event = new Event(array($handler));
        $this->assertTrue($event->publish());
    }

    public function testPublishFalseNoHandler()
    {
        $event = new Event();
        $this->assertFalse($event->publish());
    }
}

