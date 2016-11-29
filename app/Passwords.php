<?php

namespace SmartWiki;

use Illuminate\Database\Eloquent\Model;

/**
 * SmartWiki\Passwords
 *
 * @property integer $id 主键
 * @property string $token 唯一认证码
 * @property string $email 收件的邮箱
 * @property integer $is_valid 是否邮箱：0 是/1 否
 * @property string $create_time 记录创建时间
 * @property string $user_address 用户IP地址
 * @property string $send_time 邮件发送时间
 * @property string $send_result
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Passwords whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Passwords whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Passwords whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Passwords whereIsValid($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Passwords whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Passwords whereUserAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Passwords whereSendTime($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Passwords whereSendResult($value)
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
