<?php
namespace Graze\Monolog\Handler;

use Mockery as m;
use Monolog\TestCase;

class DynamoDbHandlerTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists('Aws\\DynamoDb\\DynamoDbClient')) {
            $this->markTestSkipped('aws/aws-sdk-php not installed');
        }

        $this->client  = m::mock('Aws\\DynamoDb\DynamoDbClient');
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\\Monolog\\Handler\\DynamoDbHandler', new DynamoDbHandler($this->client, 'foo'));
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Monolog\\Handler\\HandlerInterface', new DynamoDbHandler($this->client, 'foo'));
    }

    public function testGetFormatter()
    {
        $handler = new DynamoDbHandler($this->client, 'foo');
        $this->assertInstanceOf('Graze\\Monolog\\Formatter\\FlatStructureFormatter', $handler->getFormatter());
    }

    public function testHandle()
    {
        $record = $this->getRecord();
        $formatter = m::mock('Monolog\\Formatter\\FormatterInterface');
        $formatted = array('foo' => 1, 'bar' => 2);
        $handler = new DynamoDbHandler($this->client, 'foo');
        $handler->setFormatter($formatter);

        $formatter
             ->shouldReceive('format')
             ->once()
             ->with($record)
             ->andReturn($formatted);
        $this->client
             ->shouldReceive('formatAttributes')
             ->once()
             ->with(m::type('array'))
             ->andReturn($formatted);
        $this->client
             ->shouldReceive('putItem')
             ->once()
             ->with(array(
                 'TableName' => 'foo',
                 'Item' => $formatted
             ));

        $handler->handle($record);
    }
}
