<?php
namespace Graze\Monolog\Formatter;

use Monolog\Formatter\FormatterInterface;

class FlatStructureFormatter implements FormatterInterface
{
    /**
     * @var string
     */
    protected $dateFormat;

    /**
     * @param string $dateFormat
     */
    public function __construct($dateFormat = \DateTime::ISO8601)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * @param array $record
     * @return array
     */
    public function format(array $record)
    {
        $formatted = array();
        $record = $this->exposeContext($record);

        foreach ($record as $key => $value) {
            $formatted[$key] = $this->normalizeValue($value);
        }

        return $formatted;
    }

    /**
     * @param array $records
     * @return array
     */
    public function formatBatch(array $records)
    {
        $formatted = array();

        foreach ($records as $record) {
            $formatted[] = $this->format($record);
        }

        return $formatted;
    }

    /**
     * @param mixed $data
     * @return string
     */
    protected function encodeData($data)
    {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    }

    /**
     * @param array $record
     * @return array
     */
    protected function exposeContext(array $record)
    {
        if (isset($record['context'])) {
            $context = $record['context'];

            if (isset($context['exception'])) {
                $record['context']['exception'] = $this->normalizeException($context['exception']);
            }
        }

        return $record;
    }


    /**
     * @param array $data
     * @return boolean
     * @link http://stackoverflow.com/a/4254008/706138
     */
    protected function isAssociative(array $data)
    {
        return (boolean) count(array_filter(array_keys($data), 'is_string'));
    }

    /**
     * @param mixed $data
     * @return boolean
     */
    protected function isUniqueCollection($data)
    {
        return is_array($data) && !$this->isAssociative($data);
    }

    /**
     * @param Exception $e
     * @return string
     */
    protected function normalizeException(\Exception $e)
    {
        return array(
            'message' => $e->getMessage(),
            'code'  => $e->getCode(),
            'class' => get_class($e),
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'debug' => $e->getTrace()
        );
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function normalizeValue($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format($this->dateFormat);
        } elseif ($value instanceof \Exception) {
            return $this->encodeData($this->normalizeException($value));
        } elseif (!$this->isUniqueCollection($value) && (is_array($value) || is_object($value) || $value instanceof \Traversable)) {
            return $this->encodeData($value);
        }

        return $value;
    }
}
