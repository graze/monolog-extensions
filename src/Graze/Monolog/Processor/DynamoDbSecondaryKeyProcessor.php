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

class DynamoDbSecondaryKeyProcessor
{
    /**
     * @var array
     */
    private $secondaryKeys;

    /**
     * @var array
     */
    private $foundKeys = array();

    /**
     * @param array $secondaryKeys
     */
    public function __construct(array $secondaryKeys = array())
    {
        $this->secondaryKeys = $secondaryKeys;
    }

    /**
     * Retrieves array keys for use as secondary indexes
     * 
     * @param array $record
     * @return array $updated
     */
    public function __invoke(array $record)
    {
        $this->searchForKeys($record);
        $updated = $this->addSecondaryKeys($record);

        return $updated;
    }

    /**
     * tree searches $record for all array keys in $secondaryKeys
     * adds all found key-values to $foundKeys
     *
     * @param array $record
     */
    private function searchForKeys(array $record)
    {
        foreach ($record as $key => $value) {
            if (in_array($key, $this->secondaryKeys)) {
                $this->foundKeys[$key] = $value;
            }
            if (is_array($value) || is_object($value)) {
                $this->searchForKeys((array) $value);
            }
        }
    }

    /**
     * All key-values in the $foundKeys are added directly as elements of
     * $record as long as that key is currently unset in $record.
     *
     * if the key already exists in $record it is **not** replaced, similarly
     * only the first of multiple search results for a given key is used
     *
     * The key-value pairs also remain in their original location within the
     * structure of $record
     *
     * @param array $record
     */
    private function addSecondaryKeys(array $record)
    {
        foreach ($this->foundKeys as $key => $value) {
            if (!array_key_exists($key,$record)) {
                $record[$key] = $value;
            }
        }
        return $record;
    }
}
