<?php

namespace Graze\Monolog\Handler;

use Mockery;
use Mockery\MockInterface;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Raygun4php\RaygunClient;
use RuntimeException;

class RaygunHandlerIntegrationTest extends TestCase
{
    /** @var Logger */
    private $logger;
    /** @var RaygunClient|MockInterface */
    private $raygun;
    /** @var RaygunHandler */
    private $handler;

    public function setUp()
    {
        $this->raygun = Mockery::mock(RaygunClient::class);
        $this->handler = new RaygunHandler($this->raygun, Logger::NOTICE);
        $this->logger = new Logger('raygunHandlerTest', [$this->handler]);
    }

    public function testErrorWithExceptionTriggersLogger()
    {
        $exception = new RuntimeException('test exception');

        $this->raygun
            ->shouldReceive('SendException')
            ->once()
            ->with($exception, [], [], null);

        $this->logger->error('test error', ['exception' => $exception]);
    }

    public function testErrorWithErrorWillTriggerLogger()
    {
        $this->raygun
            ->shouldReceive('SendError')
            ->once()
            ->with(0, "test line error", __FILE__, 5, [], [], null);
        $this->logger->error('test line error', ['file' => __FILE__, 'line' => 5]);
    }

    public function testErrorWithNoLineWillDoNothing()
    {
        $this->logger->error('test line error', ['file' => __FILE__]);
    }

    public function testErrorWithNoFileWillDoNothing()
    {
        $this->logger->error('test line error', ['line' => __FILE__]);
    }

    public function testErrorWithNeitherWillDoNothing()
    {
        $this->logger->error('test line error');
    }
}
