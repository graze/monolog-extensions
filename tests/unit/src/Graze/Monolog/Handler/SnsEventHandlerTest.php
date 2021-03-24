<?php
namespace Graze\Monolog\Handler;

use Monolog\Test\TestCase;

class SnsEventHandlerTest extends TestCase
{
    public function setUp(): void
    {
        if (!class_exists('Aws\Sns\SnsClient')) {
            $this->markTestSkipped('aws/aws-sdk-php not installed');
        }

        $this->client = $this->getMockBuilder('Aws\Sns\SnsClient')
            ->setMethods(['formatAttributes', '__call'])
            ->disableOriginalConstructor()->getMock();
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Handler\SnsEventHandler', new SnsEventHandler($this->client, 'foo'));
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Monolog\Handler\HandlerInterface', new SnsEventHandler($this->client, 'foo'));
    }

    public function testGetFormatter()
    {
        $handler = new SnsEventHandler($this->client, 'foo');
        $this->assertInstanceOf('Graze\Monolog\Formatter\JsonDateAwareFormatter', $handler->getFormatter());
    }

    public function testIsHandling()
    {
        $handler = new SnsEventHandler($this->client, 'foo');
        $this->assertTrue($handler->isHandling($this->getRecord()));
    }

    public function testHandle()
    {
        $record = $this->getRecord();
        $formatter = $this->createMock('Monolog\Formatter\FormatterInterface');
        $formatted = ['foo' => 1, 'bar' => 2];
        $handler = new SnsEventHandler($this->client, 'foo');
        $handler->setFormatter($formatter);

        $formatter
             ->expects($this->once())
             ->method('format')
             ->with($record)
             ->will($this->returnValue($formatted));
        $this->client
             ->expects($this->once())
             ->method('__call')
             ->with('publish', [[
                 'TopicArn' => 'foo',
                 'Message' => $formatted,
             ]]);

        $handler->handle($record);
    }
}
