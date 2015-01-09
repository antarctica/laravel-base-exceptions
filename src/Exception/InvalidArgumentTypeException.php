<?php

namespace Lions\Exception;

use Antarctica\LaravelBaseExceptions\Exception\HttpException;
use Exception;

class InvalidArgumentTypeException extends HttpException {

    protected $statusCode = 400;  // Bad Request

    protected $kind = 'invalid_argument_type';

    protected $details = [
        'argument_type_error' => [
            'ARG' => [
                'Argument must be of type: [ARG_TYPE], but a value of type: [VAR_TYPE] was given.'
            ]
        ]
    ];

    protected $resolution = 'Ensure you are providing a value of the correct type: [ARG_TYPE].';

    /**
     * @var string
     */
    private $argument;
    /**
     * @var mixed
     */
    private $argumentType;
    /**
     * @var mixed
     */
    private $argumentValueType;

    /**
     * @param string $argumentName name of argument in question
     * @param mixed $validArgumentValue a value of the data type valid for the argument - its data type will be determined automatically
     * @param mixed $givenArgumentValue the value given for the argument in this instance - its data type will be determined automatically
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($argumentName, $validArgumentValue, $givenArgumentValue, $message = "", $code = 0, Exception $previous = null)
    {
        // Automatically determine correct and given data types for argument
        $this->argument = $argumentName;
        $this->argumentType = gettype($validArgumentValue);
        $this->argumentValueType = gettype($givenArgumentValue);

        $this->constructException();

        parent::__construct($message, $code, $previous);
    }

    /**
     * Fill in 'templates' with given values
     */
    private function constructException()
    {
        $this->details['argument_type_error'][$this->argument] = $this->details['argument_type_error']['ARG'];
        $this->details['argument_type_error'][$this->argument] = str_replace(['VAR_TYPE', 'ARG_TYPE'], [$this->argumentValueType, $this->argumentType], $this->details['argument_type_error'][$this->argument]);
        unset($this->details['argument_type_error']['ARG']);

        $this->resolution = str_replace('ARG_TYPE', $this->argumentType, $this->resolution);
    }
}