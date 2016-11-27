<?php

use SmartWiki\Member;
use SmartWiki\WikiConfig;


if (! function_exists('session_member')) {
    /**
     * 获取或设置登录用户 Session
     * @param Member|null $member
     * @return mixed|Member
     */
    function session_member(Member $member = null){
        if($member == null){
            $member = session('member');
        }else{
            session(['member' => $member]);
        }

        return $member;
    }
}

if(!function_exists('cookie_member')){
    /**
     * 获取或设置登录用户 Cookie
     * @param Member|null $member
     * @param bool $isExpired
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|Member|Member[]
     */
    function cookie_member(Member $member = null, $isExpired = false){
        if($isExpired){
            $cookie = cookie('login_token', '', 60 * 24 * 30*-1);
            Cookie::queue($cookie);
        }else {
            if ($member == null) {
                $cookie = Request::cookie('login_token');
                if (empty($cookie) === false) {
                    $member = Member::find($cookie['member_id']);
                }
            } else {
                $data = ['member_id' => $member->member_id, 'unique' => uniqid(), 'last_login_time' => time(), 'user_agent' => Request::header('User-Agent')];

                $cookie = cookie('login_token', $data, 60 * 24 * 30);
                Cookie::queue($cookie);
            }
        }
        return $member;
    }
}

if(!function_exists('session_project_role')){
    /**
     * 获取或设置项目访问权限
     * @param int $project_id
     * @param null $value
     * @return mixed
     */
    function session_project_role($project_id,$value = null){
        $key = 'project.role.'. $project_id;
        if(empty($value)){
            return session($key);
        }else{
             Session::put([$key => $value]);
            return session($key);
        }
    }
}
if(!function_exists('wiki_config')){
    /**
     * 获取指定键名的值如果不存在则设置默认值
     * @param string $key
     * @param null $default
     * @return mixed|null|string
     */
    function wiki_config($key,$default = null){
        $config = WikiConfig::getConfigFromCache($key);

        return empty($config) ? $default : $config;
    }
}

if(!function_exists('modify_env')) {

    /**
     * 修改ENV文件
     * @param array $data
     */
    function modify_env(array $data)
    {
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

        $contentArray->transform(function ($item) use ($data) {
            foreach ($data as $key => $value) {
                if (str_contains($item, $key)) {
                    return $key . '=' . $value;
                }
            }

            return $item;
        });

        $content = implode($contentArray->toArray(), "\n");

        \File::put($envPath, $content);
    }
}

if(!function_exists('is_can_create_project')) {
    /**
     * 判断指定用户是否能创建用户
     * @param int $member_id
     * @return bool
     */
    function is_can_create_project($member_id){
        return \SmartWiki\Project::isCanCreateProject($member_id);
    }
}