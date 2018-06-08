<?php
namespace Graze\Monolog\Processor;

use Monolog\TestCase;

class DynamoDbSecondaryIndexProcessorTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf('Graze\Monolog\Processor\DynamoDbSecondaryIndexProcessor', new DynamoDbSecondaryIndexProcessor(["foo", "bar"]));
    }

    public function testProcessor()
    {
        $processor = new DynamoDbSecondaryIndexProcessor(["foo", "bar"]);
        $datetime = new \Datetime('@400');
        $record = [
            'eventIdentifier' => "foodle",
            'timestamp' => new \Datetime('@33'),
            'data' => [
                'shoe' => [],
                'schuh' => 'german',
                'bananas' => [
                    'Bar' => 'a town in Montenegro',
                    'bar' => 'sandy',
                ],
            ],
            'metadata' => [
                'foo' => $datetime,
            ],
        ];
        $record = $processor($record);

        $this->assertArrayHasKey('foo', $record);
        $this->assertEquals($datetime, $record['foo']);
        $this->assertArrayHasKey('bar', $record);
        $this->assertEquals('sandy', $record['bar']);
    }

    public function testProcessorNoOverwrite()
    {
        $processor = new DynamoDbSecondaryIndexProcessor(["timestamp", "eventIdentifier"]);
        $timestamp = new \Datetime('@33');
        $identifier = "This should appear";
        $record = [
            'eventIdentifier' => $identifier,
            'timestamp' => $timestamp,
            'data' => [
                'bananas' => [
                    'eventIdentifier' => 'This should not appear',
                ],
            ],
            'metadata' => [
                'timestamp' => 'Also should not appear',
            ],
        ];
        $record = $processor($record);

        $this->assertEquals($timestamp, $record['timestamp']);
        $this->assertEquals($identifier, $record['eventIdentifier']);
    }
}
