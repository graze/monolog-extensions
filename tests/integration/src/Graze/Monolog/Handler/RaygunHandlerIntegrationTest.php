<?php

namespace Graze\Monolog\Handler;

use Mockery;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Raygun4php\RaygunClient;
use RuntimeException;

class RaygunHandlerIntegrationTest extends TestCase
{
    /** @var Logger */
    private $logger;
    /** @var MockObject|RaygunClient */
    private $raygun;
    /** @var RaygunHandler */
    private $handler;

    public function setUp(): void
    {
        $this->raygun = $this->createMock(RaygunClient::class);
        $this->handler = new RaygunHandler($this->raygun, Logger::NOTICE);
        $this->logger = new Logger('raygunHandlerTest', [$this->handler]);
    }

    public function testErrorWithExceptionTriggersLogger()
    {
        $exception = new RuntimeException('test exception');

        $this->raygun
            ->expects($this->once())
            ->method('SendException')
            ->with($exception, [], [], null);

        $this->logger->error('test error', ['exception' => $exception]);
    }

    public function testErrorWithErrorWillTriggerLogger()
    {
        $this->raygun->expects($this->once())
            ->method('SendError')
            ->with(0, "test line error", __FILE__, 5, [], [], null);
        $this->logger->error('test line error', ['file' => __FILE__, 'line' => 5]);
    }

    public function testErrorWithNoLineWillDoNothing()
    {
        $this->raygun->expects($this->never())->method($this->anything());

        $this->logger->error('test line error', ['file' => __FILE__]);
    }

    public function testErrorWithNoFileWillDoNothing()
    {
        $this->raygun->expects($this->never())->method($this->anything());

        $this->logger->error('test line error', ['line' => __FILE__]);
    }

    public function testErrorWithNeitherWillDoNothing()
    {
        $this->raygun->expects($this->never())->method($this->anything());

        $this->logger->error('test line error');
    }
}
