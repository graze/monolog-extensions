<?php
namespace Graze\Monolog;

use Monolog\ErrorHandler;
use PHPUnit\Framework\TestCase;

class ErrorHandlerBuilderIntegrationTest extends TestCase
{
    public function setUp(): void
    {
        $this->defaultErrorHandler = set_error_handler(function () {
        });
        $this->defaultExceptionHandler = set_exception_handler(function () {
        });
        $this->tearDown();

        $this->builder = new ErrorHandlerBuilder();
    }

    public function tearDown(): void
    {
        set_error_handler($this->defaultErrorHandler);
        set_exception_handler($this->defaultExceptionHandler);
    }

    public function testBuild()
    {
        $this->markTestSkipped('This test is no longer working and needs a rethink due to PHPUnit setting error/exception handlers');

        $handler = $this->builder->build();

        $this->assertSame(set_error_handler(function () {
        }), $this->defaultErrorHandler);
        $this->assertSame(set_exception_handler(function () {
        }), $this->defaultExceptionHandler);
    }

    public function testBuildAndRegister()
    {
        $this->markTestSkipped('This test is no longer working and needs a rethink due to PHPUnit setting error/exception handlers');

        $handler = $this->builder->buildAndRegister();

        $this->assertSame(set_error_handler(function () {
        }), [$handler, 'handleError']);
        $this->assertSame(set_exception_handler(function () {
        }), [$handler, 'handleException']);
    }
}
