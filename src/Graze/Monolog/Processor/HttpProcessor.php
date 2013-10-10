<?php
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
