<?php

namespace Graze\Monolog;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class ErrorHandlerBuilderTest extends TestCase
{
    public function setUp()
    {
        $this->builder = new ErrorHandlerBuilder();
    }

    public function testGetErrorLevelMap()
    {
        $this->assertSame([], $this->builder->getErrorLevelMap());
    }

    public function testSetErrorLevelMap()
    {
        $map = ['foo' => 'bar'];

        $this->builder->setErrorLevelMap($map);
        $this->assertSame($map, $this->builder->getErrorLevelMap());
    }

    public function testGetExceptionLevel()
    {
        $this->assertSame(LogLevel::CRITICAL, $this->builder->getExceptionLevel());
    }

    public function testSetExceptionLevel()
    {
        $this->builder->setExceptionLevel('foo');
        $this->assertSame('foo', $this->builder->getExceptionLevel());
    }

    public function testGetFatalLevel()
    {
        $this->assertNull($this->builder->getFatalLevel());
    }

    public function testSetFatalLevel()
    {
        $this->builder->setFatalLevel('foo');
        $this->assertSame('foo', $this->builder->getFatalLevel());
    }
}
