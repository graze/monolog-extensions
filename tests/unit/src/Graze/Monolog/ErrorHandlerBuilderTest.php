<?php
namespace Graze\Monolog;

use Mockery as m;

class ErrorHandlerBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->builder = new ErrorHandlerBuilder();
    }

    public function testGetName()
    {
        $this->assertSame('error', $this->builder->getName());
    }

    public function testSetName()
    {
        $this->builder->setName('foo');
        $this->assertSame('foo', $this->builder->getName());
    }

    public function testAddHandler()
    {
        $default = $this->builder->getHandlers();
        $handler = m::mock('Monolog\\Handler\\HandlerInterface');
        $this->builder->addHandler($handler);

        $this->assertSame(array_merge($default, array($handler)), $this->builder->getHandlers());
    }

    public function testGetHandlers()
    {
        $this->assertSame(array(), $this->builder->getHandlers());
    }

    public function testSetHandlers()
    {
        $handlers = array(
            m::mock('Monolog\\Handler\\HandlerInterface'),
            m::mock('Monolog\\Handler\\HandlerInterface')
        );

        $this->builder->setHandlers($handlers);
        $this->assertSame($handlers, $this->builder->getHandlers());
    }

    public function testAddProcessor()
    {
        $default = $this->builder->getProcessors();
        $processor = function(){};
        $this->builder->addProcessor($processor);

        $this->assertSame(array_merge($default, array($processor)), $this->builder->getProcessors());
    }

    public function testGetProcessors()
    {
        $processors = $this->builder->getProcessors();
        $this->assertTrue(is_array($processors), 'Array value expected');

        foreach ($processors as $processor) {
            $this->assertTrue(is_callable($processor), 'Callable value expected');
        }
    }

    public function testSetProcessors()
    {
        $processors = array(
            function(){},
            function(){}
        );

        $this->builder->setProcessors($processors);
        $this->assertSame($processors, $this->builder->getProcessors());
    }
}
