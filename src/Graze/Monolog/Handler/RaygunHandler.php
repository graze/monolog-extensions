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

use Monolog\Formatter\NormalizerFormatter;
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
     * @param integer $level
     * @param boolean $bubble
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
        $tags = array();
        $customData = array();
        $timestamp = null;
        
        if (array_key_exists('tags', $context) && is_array($context['tags'])) {
            $tags = $context['tags'];
        }
        if (array_key_exists('custom_data', $context) && is_array($context['custom_data'])) {
            $customData = $context['custom_data'];
        }
        if (array_key_exists('timestamp', $context) && is_numeric($context['timestamp'])) {
            $timestamp = $context['timestamp'];
        }
        
        if (isset($context['exception']) && $context['exception'] instanceof \Exception) {
            $this->writeException($record, $tags, $customData, $timestamp);
        } elseif (isset($context['file']) && $context['line']) {
            $this->writeError($record, $tags, $customData, $timestamp);
        } else {
            throw new \InvalidArgumentException('Invalid record given.');
        }
    }

    /**
     * @param array $record
     * @param array $tags
     * @param array $customData
     * @param int|float $timestamp
     */
    protected function writeError(array $record, array $tags, array $customData, $timestamp = null)
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
     * @param array $record
     * @param array $tags
     * @param array $customData
     * @param int|float $timestamp
     */
    protected function writeException(array $record, array $tags, array $customData, $timestamp = null)
    {
        $this->client->SendException($record['context']['exception'], $tags, $customData, $timestamp);
    }

    /**
     * @return FormatterInterface
     */
    protected function getDefaultFormatter()
    {
        return new NormalizerFormatter();
    }
}

