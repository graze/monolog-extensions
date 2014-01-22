<?php
namespace Graze\Monolog\Handler;

use Mockery as m;
use Graze\Monolog\Handler\DynamoDbEventHandler;
use Graze\Monolog\Test\EventHandlerTestCase;


class DynamoDbEventHandlerTest extends EventHandlerTestCase
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
        $this->assertInstanceOf('Graze\Monolog\Handler\DynamoDbEventHandler', new DynamoDbEventHandler($this->client, 'foo', array('bar')));
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

    public function testHandle()
    {
        $record = $this->getRecord();
        $formatter = $this->getMock('Monolog\Formatter\FormatterInterface');
        $formatted = array('foo' => 1, 'bar' => 2);
        $handler = new DynamoDbEventHandler($this->client, 'foo');
        $handler->setFormatter($formatter);

        $formatter
             ->expects($this->once())
             ->method('format')
             ->with($record)
             ->will($this->returnValue($formatted));
        $this->client
             ->expects($this->once())
             ->method('formatAttributes')
             ->with($this->isType('array'))
             ->will($this->returnValue($formatted));
        $this->client
             ->expects($this->once())
             ->method('__call')
             ->with('putItem', array(array(
                 'TableName' => 'foo',
                 'Item' => $formatted
             )));

        $handler->handle($record);
    }


    public function testSecondaryKeys()
    {
        $handler = new DynamoDbEventHandler($this->client, 'foo', array('bar','ketchup'));
        $this->assertInstanceOf('Graze\Monolog\Processor\DynamoDbSecondaryKeyProcessor', $handler->popProcessor());
    }
}
