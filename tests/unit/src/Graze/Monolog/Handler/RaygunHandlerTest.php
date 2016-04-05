<?php
namespace Graze\Monolog\Handler;

use Mockery as m;
use Monolog\TestCase;

class RaygunHandlerTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists('Raygun4php\RaygunClient')) {
            $this->markTestSkipped('mindscape/raygun4php not installed');
        }

        $this->client = m::mock('Raygun4php\RaygunClient');
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\\Monolog\\Handler\\RaygunHandler', new RaygunHandler($this->client));
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Monolog\\Handler\\HandlerInterface', new RaygunHandler($this->client));
    }

    public function testGetFormatter()
    {
        $handler = new RaygunHandler($this->client, 'foo');
        $this->assertInstanceOf('Monolog\\Formatter\\NormalizerFormatter', $handler->getFormatter());
    }

    public function testHandleError()
    {
        $record = $this->getRecord(300,
            'foo', array(
                'file' => 'bar',
                'line' => 1,
            )
        );
        $record['context']['tags'] = array('foo');
        $record['context']['timestamp'] = 1234567890;
        $record['extra'] = array('bar' => 'baz', 'tags' => array('bar'));
        $formatted = array_merge($record,
            array(
                'tags' => array('foo', 'bar'),
                'timestamp' => 1234567890,
                'custom_data' => array('bar' => 'baz')
            )
        );

        $formatter = m::mock('Monolog\\Formatter\\FormatterInterface');
        $handler = new RaygunHandler($this->client);
        $handler->setFormatter($formatter);

        $formatter
             ->shouldReceive('format')
             ->once()
             ->with($record)
             ->andReturn($formatted);
        $this->client
             ->shouldReceive('SendError')
             ->once()
             ->with(0, 'foo', 'bar', 1, array('foo', 'bar'), array('bar' => 'baz'), 1234567890);

        $handler->handle($record);
    }

    public function testHandleException()
    {
        $exception = new \Exception('foo');
        $record = $this->getRecord(300, 'foo', array('exception' => $exception));
        $record['extra'] = array('bar' => 'baz', 'tags' => array('foo', 'bar'));
        $record['extra']['timestamp'] = 1234567890;
        $formatted = array_merge($record,
            array(
                'tags' => array('foo', 'bar'),
                'timestamp' => 1234567890,
                'custom_data' => array('bar' => 'baz')
            )
        );

        $formatter = m::mock('Monolog\\Formatter\\FormatterInterface');
        $handler = new RaygunHandler($this->client);
        $handler->setFormatter($formatter);

        $formatter
             ->shouldReceive('format')
             ->once()
             ->with($record)
             ->andReturn($formatted);
        $this->client
             ->shouldReceive('SendException')
             ->once()
             ->with($exception, array('foo', 'bar'), array('bar' => 'baz'), 1234567890);

        $handler->handle($record);
    }

    /**
     * @requires PHP 7
     */
    public function testHandleThrowable()
    {
        $exception = new \TypeError('foo');
        $record = $this->getRecord(300, 'foo', array('exception' => $exception));
        $record['context']['tags'] = array('foo');
        $formatted = array_merge($record,
            array(
                'tags' => array('foo'),
                'custom_data' => array(),
                'timestamp' => null,
            )
        );

        $formatter = m::mock('Monolog\\Formatter\\FormatterInterface');
        $handler = new RaygunHandler($this->client);
        $handler->setFormatter($formatter);

        $formatter
            ->shouldReceive('format')
            ->once()
            ->with($record)
            ->andReturn($formatted);
        $this->client
            ->shouldReceive('SendException')
            ->once()
            ->with($exception, array('foo'), array(), null);

        $handler->handle($record);
    }
}
