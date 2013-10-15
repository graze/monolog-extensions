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
namespace Graze\Monolog\Processor;

class ExceptionMessageProcessor
{
    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        if (isset($record['context']['exception'])) {
            $exception = $record['context']['exception'];
            $message = $record['context']['exception']->getMessage();
            $record['message'] = 'Uncaught ' . get_class($exception) . ': ' . $message;
        }

        return $record;
    }
}
