<?php
namespace Graze\Monolog\Processor;

use Monolog\TestCase;

class FilterProcessorTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Processor\FilterProcessor', new FilterProcessor(["foo", "bar"]));
    }

    public function testProcessor()
    {
        $processor = new FilterProcessor(["foo", "bar"], 'CLASSIFIED');
        $record = [
            'foo'  => 'confidential',
            'food' => 'less secret',
            'boo'  => [
                'bar'  => 'also secret',
                'barn' => 'not secret'
            ]
        ];

        $result = $processor($record);

        $this->assertEquals('CLASSIFIED', $result['foo']);
        $this->assertEquals('less secret', $result['food']);
        $this->assertEquals('CLASSIFIED', $result['boo']['bar']);
        $this->assertEquals('not secret', $result['boo']['barn']);
    }

    public function testProcessorIgnoresArrays()
    {
        $processor = new FilterProcessor(["apples", "oranges"], 'YOU CAN\'T HANDLE THE TRUTH');
        $record = [
            'apples' => 'lots',
            'oranges' => [1,2,3]
        ];

        $result = $processor($record);

        $this->assertEquals('YOU CAN\'T HANDLE THE TRUTH', $result['apples']);
        $this->assertInternalType('array', $result['oranges']);
    }

    public function testProcessorWorksWithNullReplacementValue()
    {
        $processor = new FilterProcessor(["apples"]);
        $record = [
            'apples' => 'lots',
            'oranges' => [1,2,3]
        ];

        $result = $processor($record);

        $this->assertEquals(null, $result['apples']);
    }
}
