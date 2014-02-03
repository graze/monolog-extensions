<?php
namespace Graze\Monolog\Processor;

use Monolog\TestCase;

class DynamoDbSecondaryIndexProcessorTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Processor\DynamoDbSecondaryIndexProcessor', new DynamoDbSecondaryIndexProcessor(array("foo", "bar")));
    }

    public function testProcessor()
    {
        $processor = new DynamoDbSecondaryIndexProcessor(array("foo", "bar"));
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

    public function testProcessorNoOverwrite()
    {
        $processor = new DynamoDbSecondaryIndexProcessor(array("timestamp", "eventIdentifier"));
        $timestamp = new \Datetime('@33');
        $identifier = "This should appear";
        $record = array(
            'eventIdentifier' => $identifier,
            'timestamp' => $timestamp,
            'data' => array(
                'bananas' => array(
                    'eventIdentifier' => 'This should not appear',
                ),
            ),
            'metadata' => array(
                'timestamp' => 'Also should not appear',
            ),
        );
        $record = $processor($record);

        $this->assertEquals($timestamp, $record['timestamp']);
        $this->assertEquals($identifier, $record['eventIdentifier']);
    }

}
