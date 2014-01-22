<?php

namespace Graze\Monolog\Handler;

use Aws\Common\Aws;
use Aws\DynamoDb\DynamoDbClient;
use Monolog\Formatter\ScalarFormatter;
use Graze\Monolog\Handler\AbstractEventHandler;
use Monolog\Logger;
use Graze\Monolog\Processor\DynamoDbSecondaryKeyProcessor;


class DynamoDbHandler extends AbstractEventHandler
{
    const DATE_FORMAT = 'Y-m-d\TH:i:s.uO';

    /**
     * @var DynamoDbClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $table;


    public function __construct(DynamoDbClient $client, $table, array $secondaryKeys = array(), $level = Logger::DEBUG, $bubble = true)
    {
        if (!defined('Aws\Common\Aws::VERSION') || version_compare('3.0', Aws::VERSION, '<=')) {
            throw new \RuntimeException('The DynamoDbHandler is only known to work with the AWS SDK 2.x releases');
        }

        $this->client = $client;
        $this->table = $table;
        if(!empty($secondaryKeys)) {
            $this->pushProcessor(new DynamoDbSecondaryKeyProcessor($secondaryKeys));
        }

        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $filtered = $this->filterEmptyFields($record['formatted']);
        $formatted = $this->client->formatAttributes($filtered);

        $this->client->putItem(array(
            'TableName' => $this->table,
            'Item' => $formatted
        ));
    }

    /**
     * @param array $record
     * @return array
     */
    protected function filterEmptyFields(array $record)
    {
        return array_filter($record, function($value) {
            return !empty($value) || false === $value || 0 === $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new ScalarFormatter(self::DATE_FORMAT);
    }
}
