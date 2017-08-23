<?php
namespace Graze\Monolog\Handler;

use \Monolog\TestCase;

class DynamoDbEventHandlerTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists('Aws\DynamoDb\DynamoDbClient')) {
            $this->markTestSkipped('aws/aws-sdk-php not installed');
        }

        $this->client = $this->getMockBuilder('Aws\DynamoDb\DynamoDbClient')
            ->setMethods(array('formatAttributes', '__call'))
            ->disableOriginalConstructor()->getMock();
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Handler\DynamoDbEventHandler', new DynamoDbEventHandler($this->client, 'foo'));
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Monolog\Handler\HandlerInterface', new DynamoDbEventHandler($this->client, 'foo'));
    }

    public function testGetFormatter()
    {
        $handler = new DynamoDbEventHandler($this->client, 'foo');
        $this->assertInstanceOf('Monolog\Formatter\ScalarFormatter', $handler->getFormatter());
    }

    public function testIsHandling()
    {
        $handler = new DynamoDbEventHandler($this->client, 'foo');
        $this->assertTrue($handler->isHandling($this->getRecord()));
    }

    public function testHandle()
    {
        $record = $this->getRecord();
        $formatter = $this->getMock('Monolog\Formatter\FormatterInterface');
        $raw = ['foo' => 1, 'bar' => 2];
        $formatted = [
            'foo' => ['N' => '1'],
            'bar' => ['N' => '2']
        ];
        $handler = new DynamoDbEventHandler($this->client, 'foo');
        $handler->setFormatter($formatter);

        $formatter
             ->expects($this->once())
             ->method('format')
             ->with($record)
             ->will($this->returnValue($raw));
        $this->client
             ->expects($this->once())
             ->method('__call')
             ->with('putItem', array(array(
                 'TableName' => 'foo',
                 'Item' => $formatted
             )));

        $handler->handle($record);
    }
}
