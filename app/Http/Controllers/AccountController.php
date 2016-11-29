<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/2
 * Time: 9:38
 */

namespace SmartWiki\Http\Controllers;

use Carbon\Carbon;
use SmartWiki\Member;
use Mail;
use Cache;
use SmartWiki\Passwords;
use Illuminate\Mail\Message;

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

    /**
     * 找回密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function findPassword()
    {
        if($this->isPost()){
            $email = $this->request->input('email');
            $captcha = $this->request->input('code');
            if (empty($captcha) or strcasecmp(session('milkcaptcha'),$captcha) !== 0) {
                return $this->jsonResult(40101);
            }

            $member = Member::where('email','=',$email)->first();

            if(empty($member)){
                return $this->jsonResult(40506);
            }

            $totalCount = Passwords::where('create_time','>=', date('Y-m-d H:i:s',time() - (int)wiki_config('MAIL_TOKEN_TIME',3600)))->count();

            if($totalCount > 5){
                return $this->jsonResult(40607);
            }

            $key = md5(uniqid('find_password'));

            $passwords = new Passwords();
            $passwords->email = $email;
            $passwords->token = $key;
            $passwords->is_valid = 0;
            $passwords->user_address = $this->request->getClientIp();
            $passwords->create_time = date('Y-m-d H:i:s');
            if(!$passwords->save()){
                return $this->jsonResult(40608);
            }

            $url = route('account.modify_password',['key' => $key ]);

            Mail::queue('emails.find_password', ['url' => $url], function($message)use($passwords)
            {
                $message->to($passwords->email)->subject('SmartWiki - 找回密码!');
                $passwords->send_time =  date('Y-m-d H:i:s');
                $passwords->save();
            });
            session(['processs.data' => [
                'message' => "<p>密码重置链接已经发到您邮箱</p><p><a>{$email}</a> </p><p>请登录您的邮箱并点击密码重置链接进行密码更改</p><p><b>还没收到确认邮件?</b> 尝试到广告邮件、垃圾邮件目录里找找看</p>",
                'title' => '邮件发送成功'
            ]]);


            return $this->jsonResult(0,['url' => route('account.process_result')]);
        }
        return view('account.find_password');
    }

    /**
     * 修改密码
     * @param $key
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function modifyPassword($key)
    {
        $this->data['token'] = $key;

        if(empty($key)){
            abort(404);
        }

        $passwords = Passwords::where('token','=',$key)->where('is_valid','=','0')->where('create_time','>', date('Y-m-d H:i:s',time() - (int)wiki_config('MAIL_TOKEN_TIME',3600)))->first();

        if($this->isPost()){
            if(empty($passwords)){
                return $this->jsonResult(50001,null,'身份验证失败');
            }

            $password = $this->request->input('passowrd');
            $confirmPassword = $this->request->input('confirmPassword');


            if(empty($password)){
                return $this->jsonResult(40602);
            }
            if(empty($confirmPassword)){
                return $this->jsonResult(40603);
            }
            if(strcmp($password,$confirmPassword) !== 0){
                return $this->jsonResult(40604);
            }

            $member = Member::where('email','=',$passwords->email)->first();

            if(empty($member)){
                return $this->jsonResult(40506);
            }

            $member->member_passwd =  password_hash($password,PASSWORD_DEFAULT);

            if(!$member->save()){
                return $this->jsonResult(500);
            }
            $passwords->is_valid = 1;
            $passwords->valid_time = date('Y-m-d H:i:s');
            $passwords->save();

            return $this->jsonResult(0,['url' => route('account.login')]);
        }

        if(empty($passwords)){
            session(['processs.data' => [
                'title' => '身份验证失败',
                'message' => '身份验证失败，请重新发送邮件'
            ]]);
            return redirect(route('account.process_result'));
        }

        return view('account.modify_password',$this->data);
    }

    /**
     * 显示处理结果
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processResult()
    {
        $data = session('processs.data');
        if(empty($data) || !is_array($data)){
            return redirect(route('home.index'));
        }

        return view('account.process_result',$data);
    }
}