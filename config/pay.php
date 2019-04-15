<?php

return [
    //支付宝参数
    'alipay' => [
        'app_id'         => env ( 'ALIPAY_APP_ID' , '' ) ,
        'ali_public_key' => env ( 'ALIPAY_ALI_PUBLIC_KEY' , '' ) ,
        'private_key'    => env ( 'ALIPAY_PRIVATE_KEY' , '' ) ,
        'log'            => [
            'file' => storage_path ( 'logs/alipay.log' ) ,
        ] ,
    ] ,
    //微信支付支付参数使用微信官方提供的:https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=11_1
    'wechat' => [
        'app_id'      => 'wx426b3015555a46be' ,
        'mch_id'      => '1900009851' ,
        'key'         => '8934e7d15453e97507ef794cf7b0519d' ,
        'cert_client' => '' ,
        'cert_key'    => '' ,
        'log'         => [
            'file' => storage_path ( 'logs/wechat_pay.log' ) ,
        ] ,
    ] ,
];
