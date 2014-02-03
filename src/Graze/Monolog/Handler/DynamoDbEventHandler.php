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

use Monolog\Handler\DynamoDbHandler;

class DynamoDbEventHandler extends DynamoDbHandler
{
    /**
     * Event handlers handle all events by default
     *
     * @param array $record
     * @return boolean always returns true
     */
    public function isHandling(array $record)
    {
        return true;
    }
}
