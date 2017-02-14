<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/10 0010
 * Time: 18:44
 */

namespace SmartWiki\Observers;


use SmartWiki\Models\Member;

/**
 * 用户模型观察者
 * Class MemberObservers
 * @package SmartWiki\Observers
 */
class MemberObservers
{
    public function creating(Member $member)
    {
        $member->create_time = date('Y-m-d H:i:s');
    }
    public function updating(Member $member)
    {
        $member->modify_time = date('Y-m-d H:i:s');
        //测试账号禁止修改密码
        if(strcasecmp($member->account,'test123') === 0){
            $member->member_passwd = password_hash('test123',PASSWORD_DEFAULT);
        }
    }
}