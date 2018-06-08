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
        $record = $this->getRecord(
            300,
            'foo',
            [
                'file' => 'bar',
                'line' => 1,
            ]
        );
        $record['context']['tags'] = ['foo'];
        $record['context']['timestamp'] = 1234567890;
        $record['extra'] = ['bar' => 'baz', 'tags' => ['bar']];
        $formatted = array_merge(
            $record,
            [
                'tags' => ['foo', 'bar'],
                'timestamp' => 1234567890,
                'custom_data' => ['bar' => 'baz']
            ]
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
            ->with(0, 'foo', 'bar', 1, ['foo', 'bar'], ['bar' => 'baz'], 1234567890);

        $handler->handle($record);
    }

    public function testHandleException()
    {
        $exception = new \Exception('foo');
        $record = $this->getRecord(300, 'foo', ['exception' => $exception]);
        $record['extra'] = ['bar' => 'baz', 'tags' => ['foo', 'bar']];
        $record['extra']['timestamp'] = 1234567890;
        $formatted = array_merge(
            $record,
            [
                'tags' => ['foo', 'bar'],
                'timestamp' => 1234567890,
                'custom_data' => ['bar' => 'baz']
            ]
        );
        $formatted['context']['exception'] = [
            'class'   => get_class($exception),
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode(),
            'file'    => $exception->getFile() . ':' . $exception->getLine(),
        ];

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
            ->with($exception, ['foo', 'bar'], ['bar' => 'baz'], 1234567890);

        $handler->handle($record);
    }

    public function testHandleEmptyDoesNothing()
    {
        $record = $this->getRecord(300, 'bar');
        $record['extra'] = ['bar' => 'baz', 'tags' => ['foo', 'bar']];
        $record['extra']['timestamp'] = 1234567890;
        $formatted = array_merge($record,
            [
                'tags'        => ['foo', 'bar'],
                'timestamp'   => 1234567890,
                'custom_data' => ['bar' => 'baz'],
            ]
        );

        $formatter = m::mock('Monolog\\Formatter\\FormatterInterface');
        $handler = new RaygunHandler($this->client);
        $handler->setFormatter($formatter);

        $formatter
            ->shouldReceive('format')
            ->once()
            ->with($record)
            ->andReturn($formatted);

        $handler->handle($record);
    }

    /**
     * @requires PHP 7
     */
    public function testHandleThrowable()
    {
        $exception = new \TypeError('foo');
        $record = $this->getRecord(300, 'foo', ['exception' => $exception]);
        $record['context']['tags'] = ['foo'];
        $formatted = array_merge(
            $record,
            [
                'tags'        => ['foo'],
                'custom_data' => [],
                'timestamp'   => null,
            ]
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
            ->with($exception, ['foo'], [], null);

        $handler->handle($record);
    }
}
