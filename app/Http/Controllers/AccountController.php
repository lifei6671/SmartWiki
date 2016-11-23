<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/2
 * Time: 9:38
 */

namespace SmartWiki\Http\Controllers;

use SmartWiki\Member;

class AccountController extends Controller
{
    /**
     * 用户登录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        $cookie = $this->request->cookie('login_token');
        if(empty($cookie) === false or empty(session('member')) === false){
            $member = Member::find($cookie['member_id']);

            session_member($member);

            if($this->isGet()) {
                return redirect('/');
            }else{
                return $this->jsonResult(20001);
            }
        }
        if($this->isPost()) {
            $account = $this->request->input('account');
            $passwd = $this->request->input('passwd');

            $captcha = $this->request->input('code');

            if (empty($captcha) or strcasecmp(session('milkcaptcha'),$captcha) !== 0) {
                return $this->jsonResult(40101);
            }
            if(empty($account) or strlen($account) > 20 or strlen($account) < 3){
                return $this->jsonResult(40102);
            }
            if(empty($passwd)){
                return $this->jsonResult(40103);
            }
            $member = Member::where('account','=',$account)->where('state','=',0)->take(1)->first();

            if(empty($member) or password_verify($passwd,$member->member_passwd) === false){

                return $this->jsonResult(40401);
            }

            $member->last_login_time = date('Y-m-d H:i:s');
            $member->last_login_ip = $this->request->getClientIp();
            $member->user_agent = $this->request->header('User-Agent');
            $member->save();

            $is_remember = $this->request->input('is_remember');

            session_member($member);

            $cookie = null;
            if(strcasecmp($is_remember,'on') === 0){
                cookie_member($member);
            }

            return $this->jsonResult(20001);
        }

        return view('account.login');
    }

    /**
     * 退出登录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logout()
    {
        $this->request->session()->flush();

        cookie_member(null,true);

        $loginUrl = route('account.login');

        return redirect($loginUrl,302);
    }

    protected function initMember()
    {
        $member =new Member();
        $member->account = 'admin';
        $member->nickname = 'admin';
        $member->member_passwd = password_hash('123456',PASSWORD_DEFAULT);
        $member->email = 'longfei6671@163.com';
        $member->gorup_level = 0;
        $member->headimgurl = '/static/images/938_longfei6671.jpg';
        $member->save();
    }
}