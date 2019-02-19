<?php

namespace illmy\YhSms\Exceptions;

class GatewayErrorExcetion extends Exceptions
{
    public $raw = [];

    public function __construct($message,$code,array $raw = [])
    {
        parent::__construct($message,intval($code));

        $this->raw = $raw;
    }
}