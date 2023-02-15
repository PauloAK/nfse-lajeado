<?php

namespace PauloAK\NfseLajeado\Helpers;

class Response
{
    public bool $success = false;
    public string $requestXml = '';
    public string $responseXml = '';
    public ?array $data = null;
    public ?string $errorCode;
    public ?string $errorMessage;
    public bool $isHml = false;

    public function setSuccess(bool $success)
    {
        $this->success = $success;
        return $this;
    }

    public function setRequestXml(string $xml)
    {
        $this->requestXml = $xml;
        return $this;
    }

    public function setResponseXml(string $xml)
    {
        $this->responseXml = $xml;
        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function setErrorMessage(string $message)
    {
        $this->errorMessage = $message;
        return $this;
    }

    public function setErrorCode(string $code)
    {
        $this->errorCode = $code;
        return $this;
    }

    public function setHml(bool $isHml)
    {
        $this->isHml = $isHml;
        return $this;
    }
}