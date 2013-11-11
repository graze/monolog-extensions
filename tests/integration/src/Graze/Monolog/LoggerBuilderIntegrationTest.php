<?php
namespace Graze\Monolog;

use Monolog\Logger;

class LoggerBuilderIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->builder = new LoggerBuilder();
    }

    public function testBuild()
    {
        $logger = $this->builder->build();

        $this->assertSame(LoggerBuilder::DEFAULT_NAME, $logger->getName());
    }
}
