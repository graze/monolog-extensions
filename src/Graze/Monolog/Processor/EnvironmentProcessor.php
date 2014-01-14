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

class EnvironmentProcessor
{
    /**
     * @var array
     */
    protected $env;

    /**
     * @var string
     */
    protected $host;

    /**
     * @param array $env
     */
    public function __construct(array $env = null, $host = null)
    {
        if (null === $env) {
            $env = array_change_key_case($_SERVER, CASE_LOWER);
        }
        if (null === $host) {
            $host = gethostname();
        }

        $this->env = $env;
        $this->host = $host;
    }

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        if (null !== $this->host) {
            $record['host'] = $this->host;
        }

        if (!empty($this->env)) {
            $record['context']['env'] = $this->env;

            if (!isset($record['host']) && isset($this->env['server_name'])) {
                $record['host'] = $this->env['server_name'];
            }
        }

        return $record;
    }
}
