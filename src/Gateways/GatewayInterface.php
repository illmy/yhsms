<?php

namespace illmy\YhSms\Gateways;

interface GatewayInterface
{
    public function getName();

    public function send($to,$message);
}