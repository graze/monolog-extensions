<?php
namespace Graze\Monolog;

use Monolog\TestCase;

class EventTest extends TestCase
{
    /**
     * @return mixed
     */
    public function setupMockHandler()
    {
        $handler = $this->getMock('Monolog\Handler\HandlerInterface');
        $handler->expects($this->once())
                ->method('handle');
        return $handler;
    }

    /**
     * @param callable $callback
     *
     * @return mixed
     */
    public function setupMockHandlerWithValidationCallback(callable $callback)
    {
        $handler = $this->setupMockHandler();
        $handler->expects($this->once())
                ->method('handle')
                ->with($this->callback($callback));

        return $handler;
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Event', new Event());
    }

    public function testIdentifier()
    {
        $callback = function ($data) {
            return 'foo' === $data['eventIdentifier'];
        };
        $handler = $this->setupMockHandlerWithValidationCallback($callback);

        $event = new Event([$handler]);
        $event->setIdentifier('foo');
        $event->publish();
    }

    public function testData()
    {
        $callback = function ($data) {
            return 5 === $data['data']['foo'];
        };
        $handler = $this->setupMockHandlerWithValidationCallback($callback);

        $event = new Event([$handler]);
        $event->setData('foo', 5);
        $event->publish();
    }

    public function testMetadata()
    {
        $callback = function ($data) {
            return [] === $data['metadata']['bar'];
        };
        $handler = $this->setupMockHandlerWithValidationCallback($callback);

        $event = new Event([$handler]);
        $event->setMetadata('bar', []);
        $event->publish();
    }

    public function testPublish()
    {
        $handler = $this->setupMockHandler();

        $event = new Event([$handler]);
        $this->assertTrue($event->publish());
    }

    public function testPublishFalseNoHandler()
    {
        $event = new Event();
        $this->assertFalse($event->publish());
    }

    public function testMultipleHandlers()
    {
        $handlers = [
            $this->setupMockHandler(),
            $this->setupMockHandler(),
        ];

        $event = new Event($handlers);
        $this->assertTrue($event->publish());
    }
}
