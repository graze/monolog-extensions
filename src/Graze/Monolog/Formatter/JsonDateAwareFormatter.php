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

class JsonDateAwareFormatter extends NormalizerFormatter
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
        $data = parent::format($record);
        return $this->toJson($data, true);
    }

    /**
     * {@inheritdoc}
     *
     * @param  array $records A set of records to format
     *
     * @return mixed The formatted set of records
     */
    public function formatBatch(array $records)
    {
        return $this->format($records);
    }
}
