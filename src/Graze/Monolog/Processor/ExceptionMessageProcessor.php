<?php
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
            $record['message'] =  $record['context']['exception']->getMessage();
        }

        return $record;
    }
}
