<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2020/12/25
 * Time: 下午5:09
 */

namespace Kalamet\AlipayAuth;


use Illuminate\Support\ServiceProvider;

class AlipayAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {//运行命令时发布
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('alipay_auth.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->app->bind('alipay_auth', function($app) {
            return new AlipayAuth();
        });
    }
}