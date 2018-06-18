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

namespace Graze\Monolog\Processor;

class DynamoDbSecondaryIndexProcessor
{
    /**
     * @var array
     */
    private $secondaryIndexes;

    /**
     * @param array $secondaryIndexes
     */
    public function __construct(array $secondaryIndexes = [])
    {
        $this->secondaryIndexes = $secondaryIndexes;
    }

    /**
     * Sets up secondary indexes for dynamodb table
     *
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $foundIndexes = $this->retrieveSecondaryIndexes($record, $this->secondaryIndexes);
        return array_merge($foundIndexes, $record);
    }

    /**
     * returns an array of secondary indexes as key-value pairs that exist in $record
     *
     * @param array $record
     * @param array $keys
     * @param array $foundKeys
     *
     * @return array $foundKeys
     */
    private function retrieveSecondaryIndexes(array $record, array $keys, array &$foundKeys = [])
    {
        foreach ($record as $key => $value) {
            if (in_array($key, $keys)) {
                $foundKeys[$key] = $value;
            }
            if (is_array($value)) {
                $this->retrieveSecondaryIndexes($value, $keys, $foundKeys);
            }
        }
        return $foundKeys;
    }
}
