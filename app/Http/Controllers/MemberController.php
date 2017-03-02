<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/10/31
 * Time: 9:00
 */

namespace SmartWiki\Http\Controllers;

use SmartWiki\Models\WikiConfig;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SmartWiki\Models\Member;
use Image;
use Config;
use SmartWiki\Models\Project;
use DB;

class MemberController extends Controller
{
    /**
     * 个人资料
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|JsonResponse
     */
    public function index()
    {
        $this->data['member_index'] = true;

        if ($this->isPost()) {
            $nickname = trim($this->request->input('userNickname', ''));
            $email = $this->request->input('userEmail');
            $phone = $this->request->input('userPhone');
            $des = $this->request->input('description');

            $member = Member::find($this->member_id);
            if (empty($member)) {
                return $this->jsonResult(40506);
            }

            $member->nickname = $nickname;

            $member->email = $email;

            $member->phone = $phone;

            $member->description = $des;

            try {
                $result = Member::addOrUpdateMember($member);
                if ($result == false) {
                    return $this->jsonResult(500);
                }
                session_member($member);

                return $this->jsonResult(0);

            } catch (\Exception $ex) {
                return $this->jsonResult($ex->getCode());
            }
        }

        return view('member.index', $this->data);
    }

    /**
     * 密码修改
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function account()
    {
        $this->data['member_account'] = true;

        if($this->isPost()){
            $oldPassword = $this->request->input('oldPasswd');
            $newPassword = $this->request->input('newPasswd');
            $configPassword = $this->request->input('confirmPassword');

            if(empty($oldPassword)){
                return $this->jsonResult(40601);
            }
            if(empty($newPassword)){
                return $this->jsonResult(40602);
            }
            if(empty($configPassword)){
                return $this->jsonResult(40603);
            }
            if(strcmp($newPassword,$configPassword) !== 0){
                return $this->jsonResult(40604);
            }
            if(password_verify($oldPassword,$this->member->member_passwd) === false){
                return $this->jsonResult(40605);
            }



            $member = Member::find($this->member_id);
            $member->member_passwd = password_hash($newPassword,PASSWORD_DEFAULT);
            if(! $member->save()){
                return $this->jsonResult(500);
            }
            session_member($member);
            return $this->jsonResult(0);
        }
        return view('member.account',$this->data);
    }

    /**
     * 项目列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function projects()
    {
        $this->data['member_projects'] = true;

        $page = max(intval($this->request->input('page',1)),1);

        $this->data['lists'] = Project::getParticipationProjectList($this->member_id,$page,10);



        return view('member.projects',$this->data);
    }

    /**
     * 开发设置
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setting()
    {
        $this->data['member_setting'] = true;
        $page = max(intval($this->request->input('page',1)),1);

        $this->data['lists'] = WikiConfig::select('*')->orderBy('id','DESC')->paginate(20,'*','page',$page);

        return view('member.setting',$this->data);
    }

    /**
     * 添加或更新网站设置
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     */
    public function editSetting($id = null)
    {
        if($this->isPost()){

            $config_id = intval($this->request->input('config_id'));
            $name = trim($this->request->input('name'));
            $value = trim($this->request->input('value'));
            $key = trim($this->request->input('key'));
            $remark  = trim($this->request->input('remark'));

            if($config_id <= 0 && (empty($name) or mb_strlen($name) <3 or mb_strlen($name) > 20)){
                return $this->jsonResult(40701);
            }
            $matches = [];

            if($config_id <= 0 && (empty($key) or !preg_match('/^[a-zA-Z][a-zA-Z0-9_]{5,19}$/',$key,$matches))){
                return $this->jsonResult(40702);
            }
            $result = WikiConfig::where('id','<>',$config_id)->where(function($query)use($name,$key){
                $query->orWhere('name','=',$name)->orWhere('key','=',$key);
            })->first();

            if(empty($result) === false){
                return $this->jsonResult(40704);
            }

            if(mb_strlen($remark) > 1000){
                $remark = mb_substr($remark,0,1000);
            }

            if($config_id > 0){
                $config = WikiConfig::find($config_id);
                if(empty($config)){
                    return $this->jsonResult(40703);
                }
            }else{
                $config = new WikiConfig();
                $config->config_type = 'user';
            }
            //只能用户变量可以修改键名和键值
            if($config->config_type == 'user') {
                $config->key = $key;
                $config->name = $name;
            }
            $config->value = $value;
            $config->remark = $remark;

            if($config->save() == false){
                return $this->jsonResult(500);
            }
            return $this->jsonResult(0,['id'=>$config->id]);
        }
        $config_id = intval($id);
        if($config_id > 0){
            $config = WikiConfig::find($config_id);
            if(empty($config)){
                abort(404);
            }
            $this->data = $config;
            $this->data['member'] = $this->member;

        }
        $this->data['member_setting'] = true;

        return view('member.setting_edit',$this->data);
    }

    /**
     * 删除网站设置
     * @param null $id
     * @return JsonResponse
     */
    public function deleteSetting($id = null)
    {
        $config_id = intval($id);

        if($config_id <= 0){
            return $this->jsonResult(40703);
        }
        $config = WikiConfig::find($config_id);
        if(empty($config)){
            return $this->jsonResult(40703);
        }
        if($config->config_type == 'system'){
            return $this->jsonResult(40705);
        }
        if($config->delete() == false){
            return $this->jsonResult(500);
        }
        return $this->jsonResult(0);
    }

    /**
     * 用户列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users()
    {
        $this->data['member_users'] = true;

        $page = max(intval($this->request->input('page',1)),1);

        $members = Member::select('*')->orderBy('member_id','DESC')->paginate(20,'*','page',$page);

        $this->data['lists'] = $members;

        return view('member.users',$this->data);
    }

    /**
     * 编辑或添加用户
     * @param null|int $id
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     */
    public function editUser($id = null)
    {
        if($this->isPost()){
            $member_id = intval($this->request->input('member_id',0));
            $account = trim($this->request->input('userAccount'));

            $nickname = trim($this->request->input('userNickname',''));
            $email = $this->request->input('userEmail');
            $phone = $this->request->input('userPhone');
            $des = $this->request->input('description');
            $password = $this->request->input('userPasswd');
            $group_level = intval($this->request->input('group_level',1));

            if(in_array($group_level,[0,1,2]) == false){
                $group_level = 1;
            }

            if($member_id<= 0 and empty($password)){
                return $this->jsonResult(40103);
            }

            if($member_id > 0) {
                $member = Member::find($member_id);
                if (empty($member)) {
                    return $this->jsonResult(40506);
                }
                if($member->member_id == 1 && $group_level != 0){
                    return $this->jsonResult(40512);
                }
            }else{
                $member = new Member();
                $member->account = $account;
                $member->create_at = $this->member_id;
            }

            if(empty($password) === false) {
                $member->member_passwd = password_hash($password, PASSWORD_DEFAULT);
            }

            $member->nickname = $nickname;
            $member->email = $email;
            $member->phone =$phone;
            $member->description = $des;

            if(empty($member->headimgurl) ){
                $member->headimgurl = asset('/static/images/middle.gif');
            }

            $member->group_level = $group_level;

            try{
                $result = Member::addOrUpdateMember($member);
                if($result == false){
                    return $this->jsonResult(500);
                }

                return $this->jsonResult(0);

            }catch (\Exception $ex){
                return $this->jsonResult($ex->getCode());
            }

        }

        $member_id = intval($id);
        if($member_id > 0){
            $member = Member::find($member_id);

            if(empty($member) ){
                abort(404);
            }
            $this->data = $member;
            $this->data['member'] = $this->member;

        }

        $this->data['member_users'] = true;


        return view('member.users_edit',$this->data);
    }

    /***
     * 删除项目参与用户
     * @param int $id
     * @return JsonResponse
     */
    public function deleteUser($id = null)
    {
        $member_id = intval($id);

        if(empty($member_id) or $member_id <= 0){
            return $this->jsonResult(40506);
        }
        $member = Member::find($member_id);
        if(empty($member)){
            return $this->jsonResult(40506);
        }
        if($member->group_level == 0){
            return $this->jsonResult(40510);
        }
        $member->state = $member->state == 0 ? 1 :0;
        if($member->save() == false){
            return $this->jsonResult(500);
        }
        return $this->jsonResult(0,['state' => $member->state]);
    }
    /**
     * 用户注册
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register(Request $request)
    {
        $account =$request->input('account');
        $passwd = $request->input('passwd');
        $email = $request->input('email');

        return view('member.register');
    }


    /**
     * 处理用户头像
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $allowExt = ["jpg", "jpeg", "gif", "png"];

        $file = $this->request->file('image-file');
        //校验文件
        if($file->isValid()){
            $ext = $file -> getClientOriginalExtension(); //上传文件的后缀
            //判断是否是图片
            if(empty($ext) or in_array(strtolower($ext),$allowExt) === false){
                $data['success'] = 0;
                $data['message '] = '不允许的文件类型';

                return $this->response->json($data);
            }
            //生成文件名
            $fileName = uniqid() . '_' . dechex(microtime(true)) ;
            $filePath = public_path('uploads/user/' . date('Ym') .  '/');
            try{
                @mkdir($filePath,0777,true);
                $temp = $file->getRealPath();

                $physicalPath = $filePath . $fileName. '.' .$ext;

                $width = intval($this->request->input('width'));
                $height = intval($this->request->input('height'));
                $x = intval($this->request->input('x'));
                $y = intval($this->request->input('y'));

                $image = Image::make($temp);

                $image->crop($width,$height,$x,$y);

                $image->backup();
                $image->resize(Config::get('system.avatar_large_width',120),Config::get('system.avatar_large_height',120));

                $image->save($physicalPath);

                $image->reset();

                $small_width = Config::get('system.avatar_small_width',60);
                $small_height = Config::get('system.avatar_small_width',60);

                $image->resize($small_width,$small_height);


                $image->save($filePath . $fileName. '_small.'.$ext);

                $webPath = substr($physicalPath,strlen(public_path()));

                $data['success'] = 1;
                $data['message '] = 'ok';
                $data['url'] = url($webPath);

                $member = Member::find($this->member_id);
                $member->headimgurl = $webPath;
                if($member->save() == false){
                    return $this->jsonResult(500);
                }
                session_member($member);

                return $this->response->json($data);

            }catch (\Exception $ex){
                $data['success'] = 0;
                $data['message '] = $ex->getMessage();

                return $this->response->json($data);
            }

        }
        $data['success'] = 0;
        $data['message '] = '文件校验失败';

        return $this->response->json($data);
    }

}