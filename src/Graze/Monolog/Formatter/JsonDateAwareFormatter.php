<?php

namespace Graze\Monolog\Formatter;

use Monolog\Formatter\NormalizerFormatter;

class JsonDateAwareFormatter extends NormalizerFormatter
{
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        $data = $this->normalize($record);

        return $this->toJson($data, true);
    }
}
