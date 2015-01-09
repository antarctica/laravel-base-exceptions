<?php

namespace Lions\Exception;

use Antarctica\LaravelBaseExceptions\Exception\HttpException;
use Exception;

class InvalidArgumentValueException extends HttpException {

    protected $statusCode = 422;  // Unprocessable Entity

    protected $kind = 'invalid_argument_value';

    protected $details = [
        'argument_value_error' => [
            'ARG' => []
        ]
    ];

    private $argument;

    /**
     * @param string $argumentName name of argument in question
     * @param array $details list of specific reasons the argument value is invalid in this instance
     * @param string $resolution details of how to fix the invalid value used in this instance
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($argumentName, array $details, $resolution, $message = "", $code = 0, Exception $previous = null)
    {
        $this->argument = $argumentName;
        $this->constructException($details);
        $this->resolution = $resolution;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Fill in exception details
     *
     * @param array $details
     */
    private function constructException(array $details)
    {
        $this->details['argument_value_error'][$this->argument] = $this->details['argument_value_error']['ARG'];
        $this->details['argument_value_error'][$this->argument] = $details;
        unset($this->details['argument_value_error']['ARG']);
    }
}