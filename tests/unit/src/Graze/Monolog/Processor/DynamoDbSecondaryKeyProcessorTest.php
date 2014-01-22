<?php
namespace Graze\Monolog\Processor;

use Monolog\TestCase;
use Graze\Monolog\Processor\DynamoDbSecondaryKeyProcessor;

class DynamoDbSecondaryKeyProcessorTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Processor\DynamoDbSecondaryKeyProcessor', new DynamoDbSecondaryKeyProcessor(array("foo","bar")));
    }

    public function testProcessor()
    {
        $processor = new DynamoDbSecondaryKeyProcessor(array("foo","bar"));
        $datetime = new \Datetime('@400');
        $record = array(
            'eventIdentifier' => "foodle",
            'timestamp' => new \Datetime('@33'),
            'data' => array(
                'shoe' => array(),
                'schuh' => 'german',
                'bananas' => array(
                    'Bar' => 'a town in Montenegro',
                    'bar' => 'sandy',
                ),
            ),
            'metadata' => array(
                'foo' => $datetime,
            ),
        );
        $record = $processor($record);

        $this->assertArrayHasKey('foo', $record);
        $this->assertEquals($datetime, $record['foo']);
        $this->assertArrayHasKey('bar', $record);
        $this->assertEquals('sandy', $record['bar']);

    }
}

