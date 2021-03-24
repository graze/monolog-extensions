<?php
namespace Graze\Monolog;

use Mockery as m;
use Monolog\Test\TestCase;

class LoggerBuilderTest extends TestCase
{
    public function setUp(): void
    {
        $this->builder = new LoggerBuilder();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }

    public function testGetName()
    {
        $this->assertNull($this->builder->getName());
    }

    public function testSetName()
    {
        $this->builder->setName('foo');
        $this->assertSame('foo', $this->builder->getName());
    }

    public function testAddHandler()
    {
        $handler = m::mock('Monolog\Handler\HandlerInterface');
        $this->builder->addHandler($handler);

        $this->assertSame([$handler], $this->builder->getHandlers());
    }

    public function testGetHandlers()
    {
        $this->assertSame([], $this->builder->getHandlers());
    }

    public function testSetHandlers()
    {
        $handlers = [
            m::mock('Monolog\Handler\HandlerInterface'),
            m::mock('Monolog\Handler\HandlerInterface')
        ];

        $this->builder->setHandlers($handlers);
        $this->assertSame($handlers, $this->builder->getHandlers());
    }

    public function testAddProcessor()
    {
        $processor = function () {
        };
        $this->builder->addProcessor($processor);

        $this->assertSame([$processor], $this->builder->getProcessors());
    }

    public function testGetProcessors()
    {
        $this->assertSame([], $this->builder->getProcessors());
    }

    public function testSetProcessors()
    {
        $processors = [
            function () {
            },
            function () {
            }
        ];

        $this->builder->setProcessors($processors);
        $this->assertSame($processors, $this->builder->getProcessors());
    }
}
