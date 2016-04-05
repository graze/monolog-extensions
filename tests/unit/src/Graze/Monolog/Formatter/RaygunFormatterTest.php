<?php

namespace Graze\Monolog\Formatter;

use Monolog\TestCase;

class RaygunFormatterTest extends TestCase
{
    public function logData()
    {
        return array(
            array(
                array_merge_recursive(
                    $baseRecord = $this->getRecord(300,
                        'foo', array(
                            'file' => 'bar',
                            'line' => 1,
                        )
                    ),
                    array(
                        'context' => array(
                            'bar' => 'baz',
                            'timestamp' => 1234567890,
                            'tags' => array('foo'),
                        ),
                        'extra' => array(
                            'baz' => 'qux',
                            'tags' => array('bar'),
                        )
                    )
                ),
                array_merge(
                    $baseRecord,
                    array(
                        'tags' => array('bar', 'foo'),
                        'timestamp' => 1234567890,
                        'custom_data' => array('bar' => 'baz', 'baz' => 'qux'),
                    )
                )
            ),
            array(
                array_merge_recursive(
                    $baseRecord = $this->getRecord(300, 'foo', array('exception' => new \Exception('foo'))),
                    array(
                        'extra' => array(
                            'bar' => 'baz',
                            'tags' => array('foo', 'bar'),
                            'timestamp' => 1234567890,
                        )
                    )
                ),
                array_merge(
                    $baseRecord,
                    array(
                        'tags' => array('foo', 'bar'),
                        'timestamp' => 1234567890,
                        'custom_data' => array('bar' => 'baz'),
                    )
                )
            ),
        );
    }

    /**
     * @dataProvider logData
     * @param array $input
     * @param array $expected
     */
    public function testFormat(array $input, array $expected)
    {
        $formatter = new RaygunFormatter();
        $this->assertEquals($expected, $formatter->format($input));
    }
}
