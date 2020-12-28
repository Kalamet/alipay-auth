<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2020/12/25
 * Time: 下午3:29
 */


return [
    'gateway' => 'openapi.alipay.com',
    'app_id' => '',//申请的appid
    'private_key_path' => storage_path('auth/cert/private.pem'),
    'sign_type' => 'RSA2',
    'public_key_path' => storage_path('auth/cert/public.pem'),
    'channel' => 'apppc',
    'platform' => 'zmop',
    'biz_code' => 'SMART_FACE',
    'charset' => 'UTF-8',
];