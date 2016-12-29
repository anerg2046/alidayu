# 阿里大鱼短信库 ThinkPHP5

## 安装方法
```
composer require anerg2046/alidayu
```

>类库使用的命名空间为`\\anerg\\Alidayu`


## 典型用法
>以ThinkPHP5为例

```php
<?php

namespace app\common\service;

use anerg\Alidayu\SmsGateWay;

class Sms {

    public function send_code($mobile) {
        $code = mt_rand(1000, 9999);
        $AliSMS = new SmsGateWay();
        $AliSMS->send($mobile, ['code'=>$code], 'SMS_10210103');
    }

}

```
