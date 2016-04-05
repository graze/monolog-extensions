<?php

namespace Graze\Monolog\Formatter;

use Monolog\TestCase;

class RaygunFormatterTest extends TestCase
{

    public function testFormat()
    {
        $input = array(
            'level_name' => 'WARNING',
            'channel' => 'test',
            'message' => 'foo',
            'datetime' => new \DateTime,
            'extra' => array('baz' => 'qux', 'tags' => array('bar')),
            'context' => array(
                'file' => 'bar',
                'line' => 1,
                'bar' => 'baz',
                'timestamp' => 1234567890,
                'tags' => array('foo'),
            ),
        );
        $expected = array(
            'level_name' => 'WARNING',
            'channel' => 'test',
            'message' => 'foo',
            'datetime' => date('Y-m-d'),
            'context' => array(
                'file' => 'bar',
                'line' => 1,
            ),
            'extra' => array(),
            'tags' => array('bar', 'foo'),
            'timestamp' => 1234567890,
            'custom_data' => array('bar' => 'baz', 'baz' => 'qux'),
        );

        $formatter = new RaygunFormatter('Y-m-d');
        $this->assertEquals($expected, $formatter->format($input));
    }

    public function testFormatException()
    {
        $formatter = new RaygunFormatter('Y-m-d');
        $ex = new \Exception('foo');
        $someClass = new \stdClass();
        $someClass->foo = 'bar';
        $input = array(
            'level_name' => 'WARNING',
            'channel' => 'test',
            'message' => 'foo',
            'datetime' => new \DateTime,
            'extra' => array(
                'bar' => 'baz',
                'tags' => array('foo', 'bar'),
                'timestamp' => 1234567890,
                'someClass' => $someClass
            ),
            'context' =>  array(
                'exception' => $ex,
            ),
        );
        $formatted = $formatter->format($input);
        unset($formatted['context']['exception']['trace'], $formatted['context']['exception']['previous']);

        $this->assertEquals(array(
                'level_name' => 'WARNING',
                'channel' => 'test',
                'message' => 'foo',
                'datetime' => date('Y-m-d'),
                'context' =>  array(
                    'exception' => array(
                        'class' => get_class($ex),
                        'message' => $ex->getMessage(),
                        'code' => $ex->getCode(),
                        'file' => $ex->getFile().':'.$ex->getLine(),
                    )
                ),
                'extra' => array(),
                'tags' => array('foo', 'bar'),
                'timestamp' => 1234567890,
                'custom_data' => array('bar' => 'baz', 'someClass' => '[object] (stdClass: {"foo":"bar"})'),
            ), $formatted);
    }
}
