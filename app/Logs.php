<?php

namespace SmartWiki;

use Illuminate\Database\Eloquent\Model;

/**
 * SmartWiki\Logs
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $original_data 操作前的原数据
 * @property string $present_data 操作后的数据
 * @property string $content 日志内容
 * @property string $create_time 创建时间
 * @property integer $create_at 创建人
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Logs whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Logs whereOriginalData($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Logs wherePresentData($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Logs whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Logs whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Logs whereCreateAt($value)
 */
class Logs extends ModelBase
{
    protected $table = 'logs';
    protected $primaryKey = 'id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['id'];

    public $timestamps = false;
}
