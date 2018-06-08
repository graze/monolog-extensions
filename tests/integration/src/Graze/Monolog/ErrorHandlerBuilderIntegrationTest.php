<?php
namespace Graze\Monolog;

class ErrorHandlerBuilderIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->defaultErrorHandler = set_error_handler(function () {
        });
        $this->defaultExceptionHandler = set_exception_handler(function () {
        });
        $this->tearDown();

        $this->builder = new ErrorHandlerBuilder();
    }

    public function tearDown()
    {
        set_error_handler($this->defaultErrorHandler);
        set_exception_handler($this->defaultExceptionHandler);
    }

    public function testBuild()
    {
        $handler = $this->builder->build();

        $this->assertSame(set_error_handler(function () {
        }), $this->defaultErrorHandler);
        $this->assertSame(set_exception_handler(function () {
        }), $this->defaultExceptionHandler);
    }

    public function testBuildAndRegister()
    {
        $handler = $this->builder->buildAndRegister();

        $this->assertSame(set_error_handler(function () {
        }), [$handler, 'handleError']);
        $this->assertSame(set_exception_handler(function () {
        }), [$handler, 'handleException']);
    }
}
