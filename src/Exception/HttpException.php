<?php

namespace Antarctica\LaravelBaseExceptions\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class HttpException extends \Exception implements HttpExceptionInterface {

    protected $statusCode = 500;

    protected $headers = [];

    protected $kind = 'Exception';

    protected $details = [];

    protected $resolution = null;

    protected $resolutionURLs = [];

    /**
     * A standard property, see other documentation.
     *
     * @return int
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * A standard property, see other documentation.
     *
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * A custom property, used as a type.
     * This is mainly used in representing exceptions as errors, where the exception class is hidden.
     *
     * This property should always be returned.
     *
     * @return string
     */
    public function getKind() {
        return $this->kind;
    }

    /**
     * A custom property, used for expanding on the message of an exception.
     * This is mainly used in representing exceptions as errors, to give a collection of information (multiple validation errors etc.)
     * or more in-depth descriptive information for relevant situations.
     *
     * This property is not always returned.
     *
     * @return array
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * A custom property, used for providing instructions on how to resolve an exception.
     * This is mainly used in representing exceptions as errors, and should include brief or high level instructions only.
     *
     * You SHOULD NOT include links to documentation (for further information) use the resolutionURL property instead.
     *
     * This property is not always returned.
     *
     * @return null|string
     */
    public function getResolution() {
        return $this->resolution;
    }

    /**
     * A custom property, used for providing links to external documentation on how to resolve an exception.
     * This is mainly used in representing exceptions as errors, and should consist of URLs only.
     *
     * You SHOULD NOT include anything other than an array of relevant URLs.
     *
     * @return array
     */
    public function getResolutionURLs() {
        return $this->resolutionURLs;
    }
}