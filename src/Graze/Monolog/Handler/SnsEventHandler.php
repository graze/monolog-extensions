<?php
/*
 * This file is part of Monolog Extensions
 *
 * Copyright (c) 2014 Nature Delivered Ltd. <http://graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see  http://github.com/graze/MonologExtensions/blob/master/LICENSE
 * @link http://github.com/graze/MonologExtensions
 */

namespace Graze\Monolog\Handler;

use Aws\Sns\SnsClient;
use Graze\Monolog\Formatter\JsonDateAwareFormatter;
use Monolog\Handler\AbstractProcessingHandler;

class SnsEventHandler extends AbstractProcessingHandler
{
    const DATE_FORMAT = 'Y-m-d\TH:i:s.uO';

    /** @var SnsClient */
    private $client;
    /** @var string */
    private $topic;

    /**
     * @param SnsClient $client
     * @param string    $topic aws TopicArn
     */
    public function __construct(SnsClient $client, $topic)
    {
        $this->client = $client;
        $this->topic = $topic;
        parent::__construct();
    }

    /**
     * Event handlers handle all events by default
     *
     * @param array $record
     *
     * @return bool always returns true
     */
    public function isHandling(array $record)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $record
     */
    protected function write(array $record)
    {
        $this->client->publish([
            'TopicArn' => $this->topic,
            'Message'  => $record['formatted'],
        ]);
    }

    /**
     * @return JsonDateAwareFormatter
     */
    protected function getDefaultFormatter()
    {
        return new JsonDateAwareFormatter(self::DATE_FORMAT);
    }
}
