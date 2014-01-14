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

class HttpProcessor
{
    /**
     * @var array
     */
    protected $request;

    /**
     * @param array $request
     */
    public function __construct(array $request = null)
    {
        if (null === $request) {
            $request = $_REQUEST;
        }

        $this->request = $request;
    }

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        if (!empty($this->request)) {
            $record['context']['request'] = $this->request;
        }

        return $record;
    }
}
