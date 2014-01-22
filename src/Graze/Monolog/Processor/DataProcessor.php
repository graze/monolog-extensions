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

use Graze\Monolog\Processor\AbstractKeyValueProcessor;

class DataProcessor extends AbstractKeyValueProcessor
{
    public function __construct($key,$value)
    {
        parent::__construct($key,$value,'data');
    }
}
