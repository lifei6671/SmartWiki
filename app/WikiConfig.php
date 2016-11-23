<?php

namespace SmartWiki;

use Cache;


/**
 * SmartWiki\WikiConfig
 *
 * @property integer $id
 * @property string $name 名称
 * @property string $key 键
 * @property string $value 值
 * @property string $config_type 变量类型：system 系统内置/user 用户定义
 * @property string $remark 备注
 * @property string $create_time 创建时间
 * @property string $modify_time 修改时间
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\WikiConfig whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\WikiConfig whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\WikiConfig whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\WikiConfig whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\WikiConfig whereRemark($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\WikiConfig whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\WikiConfig whereModifyTime($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\WikiConfig whereConfigType($value)
 */
class WikiConfig extends ModelBase
{
    protected $table = 'config';
    protected $primaryKey = 'id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * 获取指定键名的值如果不存在则设置默认值
     * @param string $key
     * @param null $default
     * @return mixed|null|string
     */
    public static function getFirstOrDefault($key,$default = null)
    {
        $config = WikiConfig::where('key','=',$key)->first();

        return $config ? $config->value : $default;
    }

    /**
     * 从缓存中获取配置信息
     * @param string $key
     * @param bool $update
     * @return bool|WikiConfig|null
     */
    public static function getConfigFromCache($key, $update = false)
    {
        if(empty($key)){
            return false;
        }

        $key = 'config.key' . $key;

        $config = $update or Cache::get($key);

        if(empty($config)) {
            $config = WikiConfig::where('key','=',$key)->first();
            if(empty($config)){
                return false;
            }
            //更新后重新写入缓存
            Cache::forever($key, $config);
        }
        return $config;
    }
}
