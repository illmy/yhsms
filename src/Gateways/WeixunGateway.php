<?php

namespace illmy\YhSms\Gateways;

use illmy\YhSms\Exceptions\GatewayErrorException;
use illmy\YhSms\Traits\HasHttpRequest;

/**
 * Class WeixunGateway
 * 
 * @see http://sms.95ai.cn
 */
class WeixunGateway extends Gateway
{
    use HasHttpRequest;

    protected $host = 'sms.95ai.cn';

    protected $port = '1082';

    protected $apiMethhod = [
        'sendsms' => '/wgws/OrderServlet',
        'receipt' => '/wgws/OrderReceiptServlet',
        'addtpl'  => '/ismg1/GateWaySvl?action=applyTemplate',
        'querytpl' => '/ismg1/GateWaySvl?action=templateThrough'
    ];

    /**
     * 发送短信
     *
     * @param string $to       手机号码
     * @param string $message  内容
     * @return void
     */
    public function send($to,$message)
    {
        $params = [
            'apName' => $this->config['apName'],
            'apPassword' => $this->config['apPassword'],
            'calledNumber' => $to,
            'content' => $message
        ];

        $result = $this->post($this->buildPointUrl('sendsms'),$params);
        
        if('0' != $result['error']) {
            throw new GatewayErrorException($result['message'],$result['error'],$result);
        }

        return $result;
    }

    /**
     * 获取短信回执
     *
     * @return void
     */
    public function receipt()
    {
        $params = [
            'apName' => $this->config['apName'],
            'apPassword' => $this->config['apPassword']
        ];
        
        $result = $this->post($this->buildPointUrl('receipt'),$params);
        
        if('0' != $result['error']) {
            throw new GatewayErrorException($result['message'],$result['error'],$result);
        }
        
        return $result;
    }

    /**
     * 添加模板  
     *
     * @param string $tplcontent   模板内容
     * @param string $tpltype      模板类型  行业  营销
     * @param string $remark       备注
     * @return void
     */
    public function addTpl($tplcontent,$data = [])
    {
        //变量替换 data['varchar'] ${\d+}
        $tplcontent = preg_replace($data['varchar'], '/*'.str_repeat('*', 15).'*/', $tplcontent);
        $params = [
            'uid' => $this->config['apName'],
            'pwd' => $this->config['apPassword'],
            'templateSms' => $tplcontent,
            'templateType' => $data['tpltype'],
            'remark' => $data['remark']
        ];

        $result = $this->post($this->buildPointUrl('addtpl'),$params);

        if('0' != $result['Code']) {
            throw new GatewayErrorException();
        }

        return $result;
    }

    /**
     * 获取模板申请状态  
     *
     * @param [type] $tplid   模板ID
     * @return void
     */
    public function queryTpl($tplid)
    {
        $params = [
            'uid' => $this->config['apName'],
            'pwd' => $this->config['apPassword'],
            'templateId' => $tplid
        ];

        $result = $this->post($this->buildPointUrl('querytpl'),$params);

        if(!false) {
            throw new GatewayErrorException();
        }

        return $result;
    }

    /**
     * 生成发送URL
     *
     * @param string $method
     * @return void
     */
    protected function buildPointUrl($method)
    {
        return 'http://'.$this->host.':'.$this->port.$this->apiMethhod[$method];
    }
}