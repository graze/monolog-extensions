<?php

namespace Graze\Monolog\Formatter;

use Monolog\Test\TestCase;

class JsonDateAwareFormatterTest extends TestCase
{
    /**
     * @param mixed $data
     *
     * @return string
     */
    public function encodeJson($data)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return json_encode($data);
    }

    /**
     * @return array
     */
    public function dataFormat()
    {
        return [
            [['foo' => 'bar'], $this->encodeJson(['foo' => 'bar'])],
            [['timestamp' => new \DateTime('@0')], $this->encodeJson(['timestamp' => '1970-01-01T00:00:00+00:00'])],
        ];
    }

    /**
     * @dataProvider dataFormat
     *
     * @param array  $input
     * @param string $expected
     */
    public function testFormat(array $input, $expected)
    {
        $formatter = new JsonDateAwareFormatter();
        $this->assertEquals($expected, $formatter->format($input));
    }

    public function testFormatBatch()
    {
        $formatter = new JsonDateAwareFormatter();
        $records = [
            ['foo' => 'bar'],
            ['foo2' => 'bar2'],
        ];
        $this->assertEquals($this->encodeJson($records), $formatter->formatBatch($records));
    }
}
