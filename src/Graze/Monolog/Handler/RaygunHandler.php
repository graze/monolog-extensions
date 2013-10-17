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

        if (isset($context['exception']) && $context['exception'] instanceof \Exception) {
            $this->writeException($record);
        } elseif (isset($context['file']) && $context['line']) {
            $this->writeError($record);
        } else {
            throw new \InvalidArgumentException('Invalid record given.');
        }
    }

    /**
     * @param array $record
     */
    protected function writeError(array $record)
    {
        $context = $record['context'];
        $this->client->SendError(
            0,
            $record['message'],
            $context['file'],
            $context['line']
        );
    }

    /**
     * @param array $record
     */
    protected function writeException(array $record)
    {
        $this->client->SendException($record['context']['exception']);
    }

    /**
     * @return FormatterInterface
     */
    protected function getDefaultFormatter()
    {
        return new NormalizerFormatter();
    }
}
