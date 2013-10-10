<?php
namespace Graze\Monolog\Handler;

use Aws\DynamoDb\DynamoDbClient;
use Graze\Monolog\Formatter\FlatStructureFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class DynamoDbHandler extends AbstractProcessingHandler
{
    /**
     * @var DynamoDbClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $table;

    /**
     * @param DynamoDbClient $client
     * @param string $table
     */
    public function __construct(DynamoDbClient $client = null, $table, $level = Logger::DEBUG, $bubble = true)
    {
        $this->client = $client;
        $this->table  = $table;

        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     */
    protected function write(array $record)
    {
        $formatted = $this->client->formatAttributes($record['formatted']);

        $this->client->putItem(array(
            'TableName' => $this->table,
            'Item' => $formatted
        ));
    }

    /**
     * @return FormatterInterface
     */
    protected function getDefaultFormatter()
    {
        return new FlatStructureFormatter();
    }
}
