<?php
/*
 * This file is part of Monolog Extensions
 *
 * Copyright (c) 2013 Nature Delivered Ltd. <http://graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see  http://github.com/graze/MonologExtensions/blob/master/LICENSE
 * @link http://github.com/graze/MonologExtensions
 */
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
     * @param integer $level
     * @param boolean $bubble
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
