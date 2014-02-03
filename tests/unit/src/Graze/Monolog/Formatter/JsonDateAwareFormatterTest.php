<?php
namespace Graze\Monolog\Formatter;

use Monolog\TestCase;

class JsonDateAwareFormatterTest extends TestCase
{

    public function encodeJson($data)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return json_encode($data);
    }

    public function dataFormat()
    {
        return array(
            array(array('foo'=>'bar'), $this->encodeJson(array('foo'=>'bar'))),
            array(array('timestamp'=> new \DateTime('@0')), $this->encodeJson(array('timestamp' => '1970-01-01 00:00:00')))
        );
    }

    /**
     * @dataProvider dataFormat
     * @param array $input
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
        $records = array(
            array('foo'=>'bar'),
            array('foo2'=>'bar2'),
        );
        $this->assertEquals($this->encodeJson($records), $formatter->formatBatch($records));
    }
}
