<?php

namespace SmartWiki\Models;

use SmartWiki\Exceptions\ArgumentNullException;
use SmartWiki\Exceptions\ArgumentOutOfRangeException;
use SmartWiki\Exceptions\DataExistException;
use SmartWiki\Exceptions\DataNullException;
use SmartWiki\Exceptions\FormatException;
use SmartWiki\Exceptions\ResultNullException;


/**
 * SmartWiki\Models\Member
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
 * @method static \Illuminate\Database\Query\Builder|Member whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereAccount($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereMemberPasswd($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereGorupLevel($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|Member wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereHeadimgurl($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereCreateAt($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereModifyTime($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereLastLoginTime($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereLastLoginIp($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereUserAgent($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|Member whereState($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|Member whereGroupLevel($value)
 */
class Member extends ModelBase
{
    /**
     * 超级管理员
     */
    const SuperMember = 0;
    /**
     * 一般用户
     */
    const GeneralMember = 1;
    /**
     * 访客
     */
    const VisitorsMember = 2;

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
            if(Member::where('account','=',$member->account)->exists()){
                throw new DataExistException('账号已存在',40513);
            }
            if(Member::where('email','=',$member->email)->exists()){
                throw new DataExistException('邮箱已存在',40509);
            }
        }
        return $member->save();
    }

    /**
     * 会员登录
     * @param string $account
     * @param string $password
     * @param string|null $ip
     * @param string|null $userAgent
     * @return bool|Member
     * @throws DataNullException
     */
    public static function login($account,$password,$ip = null, $userAgent = null)
    {
        $member = Member::where('account','=',$account)->where('state','=',0)->take(1)->first();

        if(empty($member) or password_verify($password,$member->member_passwd) === false){

            throw new DataNullException('账号或密码错误',40401);
        }
        $original_data = json_encode($member,JSON_UNESCAPED_UNICODE);

        $member->last_login_time = date('Y-m-d H:i:s');
        $member->last_login_ip = $ip;
        $member->user_agent = $userAgent;
        $member->save();

        $logs = "用户 {$account} 在 {$member->last_login_time} 登录成功.IP：{$ip}，User-Agent：{$userAgent}。";
        $present_data = json_encode($member,JSON_UNESCAPED_UNICODE);

        Logs::addLogs($logs,$member->member_id,$original_data,$present_data);

        return $member;
    }

    /**
     * 获取状态为正常的用户信息
     * @param array $columns
     * @param array $where
     * @return Member|null
     */
    public static function findNormalMemberOfFirst($where = array(), $columns = ['*'])
    {
        $query = static::where('state','=',0);

        if(empty($where) === false){
            foreach ($where as $item){
                $query = call_user_func_array([$query, 'where'], $item);
            }
        }
        return $query->first($columns);
    }

    /**
     * 判断用户是否是超级管理员
     * @param int $memberId
     * @return bool
     */
    public static function isSuperMember($memberId)
    {
        $memberId = intval($memberId);
        if($memberId <= 0){
            return false;
        }
        $member = Member::find($memberId);
        //如果是管理员，则不限制
        return (empty($member) === false && $member->group_level === 0);
    }
}

