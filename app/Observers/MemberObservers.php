<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/10 0010
 * Time: 18:44
 */

namespace SmartWiki\Observers;


use SmartWiki\Member;

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
    }
}