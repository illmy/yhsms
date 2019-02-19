<?php

namespace illmy\YhSms\Exceptions;

class GatewayErrorException extends Exception
{
    public $raw = [];

    public function __construct($message,$code,array $raw = [])
    {
        parent::__construct($message,intval($code));

        $this->raw = $raw;
    }
}