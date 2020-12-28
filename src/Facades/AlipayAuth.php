<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2020/12/25
 * Time: 下午4:33
 */

namespace Kalamet\AlipayAuth\Facades;

use Illuminate\Support\Facades\Facade;

class AlipayAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'alipay_auth';
    }
}