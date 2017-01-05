<?php

/**
 * 阿里大鱼短信发送
 * @author Coeus <r.anerg@gmail.com>
 */

namespace anerg\Alidayu;

use anerg\Alidayu\Util\Sign;
use anerg\helper\Http;

class SmsGateWay {

    const GATEWAY = 'https://eco.taobao.com/router/rest';

    private $params;

    public function __construct() {
        $this->params = [
            'app_key'            => config('alidayu.app_key'),
            'timestamp'          => date("Y-m-d H:i:s", NOW_TIME),
            'format'             => 'json',
            'v'                  => '2.0',
            'sign_method'        => 'md5',
            'sms_free_sign_name' => config('alidayu.signature')
        ];
    }

    public function send($mobile, $data, $template) {
        $params                      = [];
        $params['method']            = 'alibaba.aliqin.fc.sms.num.send';
        $params['sms_type']          = 'normal';
        $params['sms_param']         = json_encode($data);
        $params['rec_num']           = $mobile;
        $params['sms_template_code'] = $template;
        $params                      = array_merge($this->params, $params);
        $params['sign']              = Sign::create($params);
        $rsp                         = Http::post(self::GATEWAY, $params);
        $rsp                         = json_decode($rsp, true);
        if (isset($rsp['alibaba_aliqin_fc_sms_num_send_response']['result']['success']) && $rsp['alibaba_aliqin_fc_sms_num_send_response']['result']['success'] == 'true') {
            return true;
        } else {
            switch ($rsp['error_response']['sub_code']) {
                case 'isv.BUSINESS_LIMIT_CONTROL':
                    $error_msg = '请勿频繁请求,稍后再试';
                    break;
                case 'isv.MOBILE_NUMBER_ILLEGAL':
                    $error_msg = '手机号码格式不正确';
                    break;
                default :
                    $error_msg = '短信系统异常';
            }
            exception($error_msg, $rsp['error_response']['code']);
        }
    }

}
