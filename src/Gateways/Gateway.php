<?php

namespace illmy\YhSms\Gateways;

/**
 * Class Gateway
 */
abstract class Gateway implements GatewayInterface
{
    const DEFAULT_TIMEOUT = 5.0;

    protected $config;

    protected $timeout;

    public function __construct($config,$timeout = '')
    {
        $this->config = $config;

        $this->timeout = $timeout;
    }

    public function getTimeout()
    {
        return $this->timeout ?:self::DEFAULT_TIMEOUT;
    }

    public function getName()
    {
        return \strtolower(str_replace([__NAMESPACE__.'\\','Gateway'],'',\get_class($this)));
    }
}