<?php
/**
 *  yhsms
 * 
 *  illmy <335111164@qq.com>
 * 
 */
namespace illmy\YhSms;

use illmy\Yhsms\Exceptions\InvalidArgumentException;;

class YhSms
{
    protected $instance = [];

    protected $config = [];

    protected $defaultGateway = [];

    /**
     * 构造方法
     *
     * @param array $config 
     * # timeout  int http请求超时时间 秒
     * # default  string  网关名称
     * # gateways array 网关参数 []
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        if(!empty($this->config['default'])) {
            $this->defaultGateway = $this->config['default'];
        }
    }

    /**
     * 返回网关实例
     *
     * @return void
     */
    protected function createGateway()
    {
        $name = $this->defaultGateway;
        if(!isset($this->instance[$name])) {
            $className = $this->formatGatewayClassName($name);
            $this->instance[$name] = $gateway = $this->makeGateway($className,'');
        }

        if($gateway instanceof GatewayInterface) {
            throw new InvalidArgumentExption(sprintf());
        }

        return $this->instance[$name];
    }

    /**
     * make 网关
     *
     * @param [type] $gateway
     * @return void
     */
    private function makeGateway($gateway)
    {
        if(!class_exists($gateway)) {
            throw new InvalidArgumentException(sprintf('Gateway "%s" not exists',$gateway));
        }

        $config = $this->config['gateways'];

        $timeout = !empty($this->config['timeout'])?:'';

        return new $gateway($config,$timeout);
    }
    
    protected static function formatGatewayClassName($name) 
    {
        if(class_exists($name)) {
            return $name;
        }
        $name = ucfirst(str_replace(['-','_',''],'',$name));
        return __NAMESPACE__."\\Gateways\\{$name}Gateway";
    }

    public function __call($name,$arguments)
    {
        return call_user_func_array([$this->createGateway(),$name],$arguments);
    }
}