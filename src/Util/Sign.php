<?php

/**
 * 签名方法
 * @author Coeus <r.anerg@gmail.com>
 */

namespace anerg\Alidayu\Util;

class Sign {

    public static function create($params) {
        if (!$params || count($params) < 1) {
            exception('参与签名的参数不能为空');
        }
        ksort($params);
        $param_str = self::buildParams($params);
        return strtoupper(md5(config('alidayu.app_secret') . $param_str . config('alidayu.app_secret')));
    }

    public static function buildParams($params, $urlencode = false) {
        $param_str = '';
        foreach ($params as $k => $v) {
            if ($k == 'sign' || $v === '') {
                continue;
            }
            $param_str .= $k;
            $param_str .= $urlencode ? urlencode($v) : $v;
        }
        return $param_str;
    }

}
