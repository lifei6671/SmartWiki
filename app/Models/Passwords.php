<?php

namespace SmartWiki\Models;


/**
 * SmartWiki\Models\Passwords
 *
 * @property integer $id 主键
 * @property string $token 唯一认证码
 * @property string $email 收件的邮箱
 * @property integer $is_valid 是否邮箱：0 是/1 否
 * @property string $create_time 记录创建时间
 * @property string $user_address 用户IP地址
 * @property string $send_time 邮件发送时间
 * @property string $send_result
 * @method static \Illuminate\Database\Query\Builder|Passwords whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Passwords whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|Passwords whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|Passwords whereIsValid($value)
 * @method static \Illuminate\Database\Query\Builder|Passwords whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|Passwords whereUserAddress($value)
 * @method static \Illuminate\Database\Query\Builder|Passwords whereSendTime($value)
 * @method static \Illuminate\Database\Query\Builder|Passwords whereSendResult($value)
 * @mixin \Eloquent
 */
class Passwords extends ModelBase
{
    protected $table = 'passwords';
    protected $primaryKey = 'id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['id'];

    public $timestamps = false;


}
