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

use Monolog\Logger;
use Monolog\Formatter\NormalizerFormatter;

class ConsoleFormatter extends NormalizerFormatter
{
    /**
     * Translates Monolog log levels to colour
     *
     * @var array
     */
    protected $logLevelToColour = array(
        Logger::DEBUG       => ['1;37', '0;36'], // Cyan
        Logger::INFO        => ['1;37', '0;32'], // Green
        Logger::NOTICE      => ['1;37', '1;33'], // Yellow
        Logger::WARNING     => ['1;37', '0;35'], // Purple
        Logger::ERROR       => ['1;37', '0;31'], // Red
        Logger::CRITICAL    => ['0;30','43'], // Black/Yellow
        Logger::ALERT       => ['1;37','45'], // White/Purple
        Logger::EMERGENCY   => ['1;37','41'], // White/Red
     );
     
     /**
      * @var array
      */
     private $columnLengthMax = [];
     
     /**
      * @var array
      */
     private $rows = [];
     
    /**
     * @param string $dateFormat The format of the timestamp: one supported by DateTime::format
     *
     * @return void
     */
    public function __construct($dateFormat = null)
    {
        parent::__construct($dateFormat);
    }
    
    /**
     * @param string $columnString,... unlimited OPTIONAL
     *
     * @return void
     */
    protected function addRow()
    {
        $arguments = func_get_args();
        
        // store the longest column so we can pad out to it on render
        foreach ($arguments as $key => $value) {
            $columnLength = strlen($value);
            
            if (!isset($this->columnLengthMax[$key]) || $columnLength > $this->columnLengthMax[$key]) {
                $this->columnLengthMax[$key] = $columnLength;
            }
        }
        
        $this->rows[] = $arguments;
    }
    
    /**
     * Formats a log record.
     *
     * @param  array $record
     *
     * @return string
     */
    public function format(array $record)
    {
        $this->addRow($record['level_name'], $record['level']);
        $this->addRow('Message', (string) $record['message']);
        $this->addRow('Time', $record['datetime']->format($this->dateFormat));
        $this->addRow('Channel', $record['channel']);
        
        if (isset($record['context']['file'])) {
            $this->addRow('File', $record['context']['file']);
            $this->addRow('Line', $record['context']['line']);
        }
        
        if (isset($record['context']['exception'])) {
            $trace = explode(PHP_EOL, $record['context']['exception']->getTraceAsString());
            $this->addRow('Trace', array_shift($trace));
            foreach ($trace as $row) {
                $this->addRow('', $row);
            }
        }
        
        return $this->render($this->getColoursByLogLevel($record['level']));
    }
    
    /**
     * @param array $colours
     *
     * @return string
     */
    protected function render($colours)
    {
        $separator = '+';
        
        foreach ($this->columnLengthMax as $columnLength) {
            $separator .= str_repeat('-', $columnLength + 2) . '+';
        }
        
        $output = $separator;
        
        foreach ($this->rows as $row) {
            $output .= PHP_EOL . '|';
            
            foreach ($this->columnLengthMax as $key => $null) {
                $cellContent = isset($row[$key]) ? $row[$key]: '';
                
                $output .= ' ' . str_pad($cellContent, $this->columnLengthMax[$key])  . ' |';
            }
        }
        
        // Create the coloured string
        return "\n\033[{$colours[0]}m\033[{$colours[1]}m" . $output . PHP_EOL . $separator . PHP_EOL . "\033[0m\n";
    }
    
    /**
     * @param $logLevel
     *
     * @return array
     */
    protected function getColoursByLogLevel($logLevel)
    {
        return $this->logLevelToColour[$logLevel];
    }
}
