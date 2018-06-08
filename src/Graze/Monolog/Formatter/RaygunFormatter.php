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

namespace Graze\Monolog\Formatter;

use Monolog\Formatter\NormalizerFormatter;

class RaygunFormatter extends NormalizerFormatter
{
    /**
     * {@inheritdoc}
     *
     * @param  array $record A record to format
     *
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        $record = parent::format($record);

        $record['tags'] = [];
        $record['custom_data'] = [];
        $record['timestamp'] = null;

        foreach (['extra', 'context'] as $source) {
            if (array_key_exists('tags', $record[$source]) && is_array($record[$source]['tags'])) {
                $record['tags'] = array_merge($record['tags'], $record[$source]['tags']);
            }
            if (array_key_exists('timestamp', $record[$source]) && is_numeric($record[$source]['timestamp'])) {
                $record['timestamp'] = $record[$source]['timestamp'];
            }
            unset($record[$source]['tags'], $record[$source]['timestamp']);
        }

        $record['custom_data'] = $record['extra'];
        $record['extra'] = [];
        foreach ($record['context'] as $key => $item) {
            if (!in_array($key, ['file', 'line', 'exception'])) {
                $record['custom_data'][$key] = $item;
                unset($record['context'][$key]);
            }
        }

        return $record;
    }
}
