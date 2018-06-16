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

use Graze\Monolog\Formatter\RaygunFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Raygun4php\RaygunClient;

class RaygunHandler extends AbstractProcessingHandler
{
    /**
     * @var RaygunClient
     */
    protected $client;

    /**
     * @param RaygunClient $client
     * @param int          $level
     * @param bool         $bubble
     */
    public function __construct(RaygunClient $client, $level = Logger::DEBUG, $bubble = true)
    {
        $this->client = $client;

        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     */
    protected function write(array $record)
    {
        $context = $record['context'];

        if (isset($context['exception'])
            && (
                $context['exception'] instanceof \Exception
                || (PHP_VERSION_ID > 70000 && $context['exception'] instanceof \Throwable)
            )
        ) {
            $this->writeException(
                $record,
                $record['formatted']['tags'],
                $record['formatted']['custom_data'],
                $record['formatted']['timestamp']
            );
        } elseif (isset($context['file']) && isset($context['line'])) {
            $this->writeError(
                $record['formatted'],
                $record['formatted']['tags'],
                $record['formatted']['custom_data'],
                $record['formatted']['timestamp']
            );
        }
        // do nothing if its not an exception or an error
    }

    /**
     * @param array     $record
     * @param array     $tags
     * @param array     $customData
     * @param int|float $timestamp
     */
    protected function writeError(array $record, array $tags = [], array $customData = [], $timestamp = null)
    {
        $context = $record['context'];
        $this->client->SendError(
            0,
            $record['message'],
            $context['file'],
            $context['line'],
            $tags,
            $customData,
            $timestamp
        );
    }

    /**
     * @param array     $record
     * @param array     $tags
     * @param array     $customData
     * @param int|float $timestamp
     */
    protected function writeException(array $record, array $tags = [], array $customData = [], $timestamp = null)
    {
        $this->client->SendException($record['context']['exception'], $tags, $customData, $timestamp);
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface
     */
    protected function getDefaultFormatter()
    {
        return new RaygunFormatter();
    }
}

