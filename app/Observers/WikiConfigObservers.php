<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/10 0010
 * Time: 18:40
 */

namespace SmartWiki\Observers;

use Cache;
use SmartWiki\Models\WikiConfig;

/**
 * 配置文件观察者
 * Class WikiConfigObservers
 * @package SmartWiki\Observers
 */
class WikiConfigObservers
{
    public function creating(WikiConfig $config)
    {
        $config->create_time =  date('Y-m-d H:i:s');
    }
    public function updating(WikiConfig $config)
    {
        $config->modify_time =  date('Y-m-d H:i:s');
        $key = 'config.key.' . $config->key;
        //当更新时移除缓存
        Cache::forget($key);
    }

    public function updated(WikiConfig $config)
    {
        $key = 'config.key' . $config->key;
        //更新后重新写入缓存
        Cache::forever($key,$config);
    }
}