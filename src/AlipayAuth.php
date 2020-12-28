<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2020/12/25
 * Time: 下午4:31
 */

namespace Kalamet\AlipayAuth;

use Alipay\EasySDK\Kernel\Config;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use Alipay\EasySDK\Member\Identification\Models\IdentityParam;
class AlipayAuth
{
    public function getOptions()
    {
        $config = new Config();
        $config->protocol = 'https';
        $config->gatewayHost = config('alipay_auth.gateway');
        $config->signType = 'RSA2';
        $config->appId = config('alipay_auth.app_id');
        $config->merchantPrivateKey = file_get_contents(config('alipay_auth.private_key_path'));
        $config->alipayPublicKey = file_get_contents(config('alipay_auth.public_key_path'));
        return $config;
    }

    public function getCertifyId($user, $realname, $cert_no)
    {
        $outerOrderNo = $user->id  . time() . mt_rand(10000, 99999);
        $bizCode = config('biz_code', 'SMART_FACE');
        $identityParam  = new IdentityParam();
        $identityParam->identityType = 'CERT_INFO';
        $identityParam->certType = 'IDENTITY_CARD';
        $identityParam->certName = $realname;
        $identityParam->certNo = $cert_no;
        $config = $this->getOptions();
        Factory::setOptions($config);
        $result = Factory::member()->identification()->init($outerOrderNo, $bizCode, $identityParam, $config);
        $response_check = new ResponseChecker();
        if ($response_check->success($result)) {
            $certifyId = $result->certifyId;
        } else {
            //失败
            return ['message' => $result->msg . ':' . $result->subMsg];
        }
        if (isset($certifyId) && !empty($certifyId)) {
            //存储$certifyId 并返回url
            $r = Factory::member()->identification()->certify($certifyId);
            if ($response_check->success($r)) {
                return ['certify_id' => $certifyId, 'url' => $r->body];
            } else {
                return ['message' => $result->msg . ':' . $result->subMsg];
            }
        }
        return false;
    }

    public function queryAuthStatus($certifyId)
    {
        $config = $this->getOptions();
        Factory::setOptions($config);
        $result = Factory::member()->identification()->query($certifyId);
        $response_check = new ResponseChecker();
        if ($response_check->success($result)) {
            if ($result->passed == 'T') {
                return true;
            }
        } else {
            return ['message' => $result->msg . ':' . $result->subMsg];
        }
        return false;
    }

}