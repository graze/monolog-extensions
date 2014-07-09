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

class FilterProcessor
{
    /**
     * @var array
     */
    protected $keys;

    /**
     * @var string
     */
    protected $replacement;

    /**
     * @param array $keys
     * @param string $replacement
     */
    public function __construct(array $keys = array(), $replacement = null)
    {
        $this->keys = $keys;
        $this->replacement = $replacement;
    }

    /**
     * @param array $record
     */
    public function __invoke(array $record)
    {
        array_walk_recursive($record, array($this, 'filterValue'));
        return $record;
    }

    /**
     * @param mixed &$value
     * @param string $key
     */
    protected function filterValue(&$value, $key)
    {
        if (in_array($key, $this->keys)) {
            $value = $this->replacement;
        }
    }
}
