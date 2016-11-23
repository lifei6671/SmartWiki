<?php

namespace SmartWiki;

use SmartWiki\Exceptions\ArgumentNullException;
use SmartWiki\Exceptions\ArgumentOutOfRangeException;
use SmartWiki\Exceptions\DataExistException;
use SmartWiki\Exceptions\FormatException;
use SmartWiki\Exceptions\ResultNullException;


/**
 * SmartWiki\Member
 *
 * @property integer $member_id
 * @property string $account 账号
 * @property string $member_passwd 密码
 * @property string $nickname 昵称
 * @property string $description 描述
 * @property integer $group_level 用户基本：0 超级管理员，1 普通用户，2 访客
 * @property string $email 用户邮箱
 * @property string $phone 手机号码
 * @property string $headimgurl 用户头像
 * @property string $remember_token 用户session
 * @property string $create_time 创建时间
 * @property integer $create_at 创建人
 * @property string $modify_time 修改时间
 * @property string $last_login_time 最后登录时间
 * @property string $last_login_ip 最后登录IP
 * @property string $user_agent 最后登录浏览器信息
 * @property string $version 当前时间戳
 * @property boolean $state
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereAccount($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereMemberPasswd($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereGorupLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereHeadimgurl($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereCreateAt($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereModifyTime($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereLastLoginTime($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereLastLoginIp($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereUserAgent($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartWiki\Member whereState($value)
 * @mixin \Eloquent
 */
class Member extends ModelBase
{
    protected $table = 'member';
    protected $primaryKey = 'member_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['member_id','last_login_time'];

    public $timestamps = false;

    /**
     * 添加或更新用户信息
     * @param Member $member
     * @return bool
     * @throws \Exception
     */
    public static function addOrUpdateMember(Member &$member)
    {
        if($member->member_id <= 0 and empty($member->account)){
            throw new ArgumentNullException('账号不能为空',40507);
        }
        $matches = array();
        if($member->member_id<=0 and !preg_match('/^[a-zA-Z][a-zA-Z0-9_]{4,19}$/',$member->account,$matches)){
            throw new FormatException('账号必须以英文字母开头并且大于5个字符小于20个字符',40508);
        }

        if(empty($member->nickname) === false and mb_strlen($member->nickname) > 20){
            throw new FormatException('用户昵称最少3个字符，最多20字符',40501);
        }
        if(empty($member->email)){
            throw new ArgumentNullException('用户邮箱不能为空',40502);
        }
        if(!filter_var ($member->email, FILTER_VALIDATE_EMAIL )){
            throw new FormatException('用户邮箱不合法',40503);
        }
        if(empty($phone) === false and strlen($member->phone) > 20){
            throw new FormatException('手机号码不合法',40504);
        }
        if(empty($member->description) === false and mb_strlen($member->description) > 500){
            throw new ArgumentOutOfRangeException('描述最多500字',40505);
        }



        if($member->member_id > 0) {

            if(empty(Member::where('email','=',$member->email)->where('member_id','<>',$member->member_id)->first()) === false){
                throw new DataExistException('邮箱已存在',40509);
            }

            $user = Member::find($member->member_id);
            if(empty($user)){
                throw new ResultNullException('用户不存在',40506);
            }

        }else{
            if(empty(Member::where('email','=',$member->email)->first()) === false){
                throw new DataExistException('邮箱已存在',40509);
            }
        }
        return $member->save();
    }
}

