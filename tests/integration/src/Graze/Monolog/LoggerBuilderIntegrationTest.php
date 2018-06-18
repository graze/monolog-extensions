<?php

namespace Graze\Monolog;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class LoggerBuilderIntegrationTest extends TestCase
{
    /** @var LoggerBuilder */
    private $builder;

    public function setUp()
    {
        $this->builder = new LoggerBuilder();
    }

    /**
     * @param Logger $logger
     */
    public function assertDefaultHandlers(Logger $logger)
    {
        $handlers = [];
        $exception = null;
        do {
            try {
                $handlers[] = $handler = $logger->popHandler();
            } catch (\Exception $e) {
                $exception = $e;
            }
        } while (is_null($exception));

        $this->assertSame([], $handlers, 'There are more handlers defined than should be');
    }

    /**
     * @param Logger $logger
     */
    public function assertDefaultProcessors(Logger $logger)
    {
        $processors = [];
        $exception = null;
        do {
            try {
                $processors[] = $processor = $logger->popProcessor();
            } catch (\Exception $e) {
                $exception = $e;
            }
        } while (is_null($exception));

        $this->assertInstanceOf('Graze\Monolog\Processor\ExceptionMessageProcessor', array_shift($processors));
        $this->assertInstanceOf('Graze\Monolog\Processor\EnvironmentProcessor', array_shift($processors));
        $this->assertInstanceOf('Graze\Monolog\Processor\HttpProcessor', array_shift($processors));
        $this->assertSame([], $processors, 'There are more processors defined than should be');
    }

    public function testBuild()
    {
        $logger = $this->builder->build();

        $this->assertSame(LoggerBuilder::DEFAULT_NAME, $logger->getName());
        $this->assertDefaultHandlers($logger);
        $this->assertDefaultProcessors($logger);
    }
}
