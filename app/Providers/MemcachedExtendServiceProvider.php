<?php

namespace SmartWiki\Providers;

use Illuminate\Cache\Repository;
use Illuminate\Cache\MemcachedStore;
use Illuminate\Support\ServiceProvider;

use Cache;
use Memcached;

class MemcachedExtendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        Cache::extend('MemcachedExtend', function ($app) {
            // 从配置文件中读取 Memcached 服务器配置
            $servers = $app['config']['cache.stores.MemcachedExtend.servers'];


            // 利用 Illuminate\Cache\MemcachedConnector 类来创建新的 Memcached 对象
            $memcached = $app['memcached.connector']->connect($servers);

            // 如果服务器上的 PHP Memcached 扩展支持 SASL 认证
            if (ini_get('memcached.use_sasl') && isset($app['config']['cache.storess.MemcachedExtend.memcached_user']) && isset($app['config']['cache.storess.MemcachedExtend.memcached_pass'])) {

                // 从配置文件中读取 sasl 认证用户名
                $user = $app['config']['cache.storess.MemcachedExtend.memcached_user'];

                // 从配置文件中读取 sasl 认证密码
                $pass = $app['config']['cache.storess.MemcachedExtend.memcached_pass'];

                // 指定用于 sasl 认证的账号密码
                $memcached->setSaslAuthData($user, $pass);
            }

            //扩展
            if (isset($app['config']['cache.stores.MemcachedExtend.options'])) {
                foreach ($app['config']['cache.stores.MemcachedExtend.options'] as $key => $option) {
                    $memcached->setOption($key, $option);
                }
            }

            // 从配置文件中读取缓存前缀
            $prefix = $app['config']['cache.prefix'];

            // 创建 MemcachedStore 对象
            $store = new MemcachedStore($memcached, $prefix);

            // 创建 Repository 对象，并返回
            return new Repository($store);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
