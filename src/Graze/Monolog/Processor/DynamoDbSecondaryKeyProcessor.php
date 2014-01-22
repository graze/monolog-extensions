<?php

namespace Graze\Monolog\Processor;

class DynamoDbSecondaryKeyProcessor
{
    /**
     * @var array
     */
    protected $secondaryKeys;

    /**
     * @var array
     */
    protected $foundKeys;

    /**
     * @param array $secondaryKeys
     */
    public function __construct(array $secondaryKeys = array())
    {
        $this->secondaryKeys = $secondaryKeys;
        $this->foundKeys = array();
    }

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $this->searchForKeys($record);
        $updated = $this->addSecondaryKeys($record);

        return $updated;
    }

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
