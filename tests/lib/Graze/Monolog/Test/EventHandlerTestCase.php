<?php
namespace Graze\Monolog\Test;

use Monolog\TestCase;
use Monolog\Logger;

abstract class EventHandlerTestCase extends TestCase
{
    public function levels()
    {
        return array(
            array(Logger::DEBUG),
            array(Logger::INFO),
            array(Logger::NOTICE),
            array(Logger::WARNING),
            array(Logger::ERROR),
            array(Logger::CRITICAL),
            array(Logger::EMERGENCY),
        );
    }
    /**
     * @dataProvider levels
     * @param int $level
     */
    public function testIsHandling($level)
    {
        $handler = $this->getMockForAbstractClass('Graze\Monolog\Handler\AbstractEventHandler', array($level, false));
        $this->assertTrue($handler->isHandling($this->getRecord()));
    }
}
