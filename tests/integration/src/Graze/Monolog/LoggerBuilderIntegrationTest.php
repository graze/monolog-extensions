<?php
namespace Graze\Monolog;

use Monolog\Logger;

class LoggerBuilderIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->builder = new LoggerBuilder();
    }

    public function assertDefaultHandlers(Logger $logger)
    {
        $handlers = array();
        do {
            try {
                $handlers[] = $handler = $logger->popHandler();
            } catch (\Exception $e) {
            }
        } while (!isset($e));

        $this->assertSame(array(), $handlers, 'There are more handlers defined than should be');
    }

    public function assertDefaultProcessors(Logger $logger)
    {
        $processors = array();
        do {
            try {
                $processors[] = $processor = $logger->popProcessor();
            } catch (\Exception $e) {
            }
        } while (!isset($e));

        $this->assertInstanceOf('Graze\Monolog\Processor\ExceptionMessageProcessor', array_shift($processors));
        $this->assertInstanceOf('Graze\Monolog\Processor\EnvironmentProcessor', array_shift($processors));
        $this->assertInstanceOf('Graze\Monolog\Processor\HttpProcessor', array_shift($processors));
        $this->assertSame(array(), $processors, 'There are more processors defined than should be');
    }

    public function testBuild()
    {
        $logger = $this->builder->build();

        $this->assertSame(LoggerBuilder::DEFAULT_NAME, $logger->getName());
        $this->assertDefaultHandlers($logger);
        $this->assertDefaultProcessors($logger);
    }
}
