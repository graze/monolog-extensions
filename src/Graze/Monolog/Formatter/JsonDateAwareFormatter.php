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
     */
    public function format(array $record)
    {
        $data = parent::format($record);

        return $this->toJson($data, true);
    }

    /**
     * {@inheritdoc}
     */
    public function formatBatch(array $records)
    {
        return $this->format($records);
    }
}
