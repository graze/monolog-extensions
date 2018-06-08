<?php

namespace Graze\Monolog\Formatter;

use Monolog\TestCase;

class RaygunFormatterTest extends TestCase
{

    public function testFormat()
    {
        $input = [
            'level_name' => 'WARNING',
            'channel' => 'test',
            'message' => 'foo',
            'datetime' => new \DateTime,
            'extra' => ['baz' => 'qux', 'tags' => ['bar']],
            'context' => [
                'file' => 'bar',
                'line' => 1,
                'bar' => 'baz',
                'timestamp' => 1234567890,
                'tags' => ['foo'],
            ],
        ];
        $expected = [
            'level_name' => 'WARNING',
            'channel' => 'test',
            'message' => 'foo',
            'datetime' => date('Y-m-d'),
            'context' => [
                'file' => 'bar',
                'line' => 1,
            ],
            'extra' => [],
            'tags' => ['bar', 'foo'],
            'timestamp' => 1234567890,
            'custom_data' => ['bar' => 'baz', 'baz' => 'qux'],
        ];

        $formatter = new RaygunFormatter('Y-m-d');
        $this->assertEquals($expected, $formatter->format($input));
    }

    public function testFormatException()
    {
        $formatter = new RaygunFormatter('Y-m-d');
        $ex = new \Exception('foo');
        $someClass = new \stdClass();
        $someClass->foo = 'bar';
        $input = [
            'level_name' => 'WARNING',
            'channel' => 'test',
            'message' => 'foo',
            'datetime' => new \DateTime,
            'extra' => [
                'bar' => 'baz',
                'tags' => ['foo', 'bar'],
                'timestamp' => 1234567890,
                'someClass' => $someClass
            ],
            'context' =>  [
                'exception' => $ex,
            ],
        ];
        $formatted = $formatter->format($input);
        unset($formatted['context']['exception']['trace'], $formatted['context']['exception']['previous']);

        $this->assertEquals([
                'level_name' => 'WARNING',
                'channel' => 'test',
                'message' => 'foo',
                'datetime' => date('Y-m-d'),
                'context' =>  [
                    'exception' => [
                        'class' => get_class($ex),
                        'message' => $ex->getMessage(),
                        'code' => $ex->getCode(),
                        'file' => $ex->getFile().':'.$ex->getLine(),
                    ]
                ],
                'extra' => [],
                'tags' => ['foo', 'bar'],
                'timestamp' => 1234567890,
                'custom_data' => ['bar' => 'baz', 'someClass' => '[object] (stdClass: {"foo":"bar"})'],
            ], $formatted);
    }
}
