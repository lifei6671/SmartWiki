<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/10/25
 * Time: 11:32
 */

namespace SmartWiki\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Log;
use SmartWiki\Models\Member;
use SmartWiki\Models\Project;
use SmartWiki\Models\Relationship;
use Illuminate\Auth\Access\AuthorizationException;

class ProjectController extends Controller
{
    /**
     * 创建项目
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        //如果非管理员用户并且非普通用户则禁止创建项目
        if($this->member->group_level != 0 && $this->member->group_level != 1){
            abort(403);
        }

        $projectName = trim($this->request->input('projectName'));
        $description = trim($this->request->input('description',null));
        $isPasswd = $this->request->input('projectPasswd','1');
        $passwd = trim($this->request->input('projectPasswdInput',null));

        $project = new Project();
        $project->project_name = $projectName;
        $project->description = $description;
        $project->project_open_state = $isPasswd;
        $project->project_password = $passwd;
        $project->create_at = $this->member_id;

        try{
            $project->addOrUpdate();

        }catch (\Exception $ex){
            if($ex->getCode() == 500){
                return $this->jsonResult(40205,null,$ex->getMessage());
            }else{
                return $this->jsonResult($ex->getCode());
            }
        }
        $this->data = $project->toArray();

        $this->data['doc_count'] = 0;

        $view = view('widget.project',$this->data);
        $this->data = array();

        $this->data['body'] = $view->render();

        return $this->jsonResult(20002,$this->data);
    }

    /**
     * 删除项目
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     */
    public function delete($id)
    {
        $this->data['member_projects'] = true;
        $project_id = intval($id);
        if ($project_id <= 0) {
            if($this->request->ajax()){
                return $this->jsonResult(50502);
            }
            abort(404);
        }
        $project = Project::find($project_id);
        if (empty($project)) {
            if($this->request->ajax()) {
                return $this->jsonResult(40206);
            }
            abort(404);
        }
        //如果不是项目的拥有者并且不是超级管理员
        if (!Project::isOwner($project_id,$this->member->member_id) && $this->member->group_level != 0) {
            if($this->request->ajax()) {
                return $this->jsonResult(40305);
            }
            abort(403);
        }

        if($this->isPost()) {
            $password = $this->request->get('password');
            $member = Member::find($this->member_id);
            //如果密码错误
            if(password_verify($password,$member->member_passwd) === false){
                return $this->jsonResult(40606);
            }
            try{
                Project::deleteProjectByProjectId($project_id);
                return $this->jsonResult(0);
            }catch (\Exception $ex){
                if($ex->getCode() == 500){
                    Log::error($ex->getMessage(),['trace'=>$ex->getTrace(),'file'=>$ex->getFile(),'line'=>$ex->getLine()]);
                    return $this->jsonResult(500,null,'删除失败');
                }else{
                    return $this->jsonResult($ex->getCode());
                }
            }
        }
        $this->data['project'] = $project;

        return view('project.delete',$this->data);
    }
    /**
     * 编辑项目或创建
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|JsonResponse
     * @throws AuthorizationException
     */
    public function edit($id = null)
    {
        $project_id = intval($id);
        //如果是访客则不能创建项目
        if($project_id <=0 && is_can_create_project($this->member_id) === false){
            abort(403);
        }
        $project = null;
        //如果项目不存在
        if($project_id > 0 && empty($project = Project::find($id)) ){
            if($this->isPost()){
                return $this->jsonResult(40206);
            }else{
                abort(404);
            }
        }

        //如果没有编辑权限
        if($project_id > 0 &&  $this->member->group_level != 0 && Project::hasProjectEdit($project_id,$this->member_id) === false){

            if($this->isPost()){
                return $this->jsonResult(40305);
            }else{
                abort(403);
            }
        }
        //如果不是项目的拥有者并且不是超级管理员
        if ($project_id > 0 && !Project::isOwner($project_id,$this->member->member_id) && $this->member->group_level != 0) {

            abort(403);
        }

        //如果是修改项目
        if($this->isPost()){
            $name = trim($this->request->input('name'));
            $description = trim($this->request->input('description'));
            $open_state = $this->request->input('state');
            $password = $this->request->input('password');
            $version = $this->request->input('version');
            if(empty($project)) {
                $project = new Project();
            }
            $project->project_name = $name;
            $project->description = $description;
            $project->project_open_state = $open_state;
            $project->project_password = $password;
            $project->version = $version;
            $project->create_at = $this->member_id;

            try{
                if($project->addOrUpdate()) {
                    $data['project_id'] = $project->project_id;
                    $data['url'] = route('project.edit',['id'=>$project->project_id]);

                    return $this->jsonResult(0,$data);
                }else{
                    return $this->jsonResult(500);
                }
            }catch (\Exception $ex){
                if($ex->getCode() == 500){
                    return $this->jsonResult(40205,null,$ex->getMessage());
                }else{
                    return $this->jsonResult((int)$ex->getCode());
                }
            }
        }
        $this->data['title'] = '编辑项目';

        if(empty($project)){
            $project = new Project();
            $project->project_open_state = 0;
            $this->data['title'] = '添加项目';
            $this->data['is_owner'] = false;
        }else{
            $this->data['is_owner'] = Project::isOwner($project_id,$this->member->member_id) ;
        }

        $this->data['project'] = $project;
        $this->data['member_projects'] = true;

        return view('project.edit',$this->data);
    }

    /**
     * 项目参与成员列表
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function members($id)
    {
        $project_id = intval($id);

        if(empty($project_id)){
            abort(404);
        }

        $project = Project::find($project_id);
        if(empty($project)){
            abort(404);
        }

        //如果不是项目的拥有者并且不是超级管理员
        if (!Project::isOwner($project_id,$this->member->member_id) && $this->member->group_level != 0) {
            return $this->jsonResult(40305);
        }

        $this->data = $project;
        $this->data['member'] = $this->member;
        $this->data['member_projects'] = true;
        $this->data['users'] = Project::getProjectMemberByProjectId($project_id);
        return view('project.members',$this->data);
    }

    /**
     * 添加或删除项目组用户
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMember($id)
    {
        $project_id = intval($id);
        $type = trim($this->request->input('type'));
        $account = trim($this->request->input('account'));

        if (empty($project_id)) {
            return $this->jsonResult(50502);
        }
        $project = Project::find($project_id);
        if (empty($project)) {
            return $this->jsonResult(40206);
        }
        //如果不是项目的拥有者并且不是超级管理员
        if (!Project::isOwner($project_id,$this->member->member_id) && $this->member->group_level != 0) {
            return $this->jsonResult(40305);
        }
        $member = Member::findNormalMemberOfFirst([['account', '=', $account]]);
        if (empty($member)) {
            return $this->jsonResult(40506);
        }

        if($member->state == 1){
            return $this->jsonResult(40511);
        }
        $data = null;
        $rel = Relationship::where('project_id', '=', $project_id)->where('member_id', '=', $member->member_id)->first();
        //如果是添加成员
        if (strcasecmp($type, 'add') === 0) {
            if (empty($rel) === false) {
                return $this->jsonResult(40801);
            }
            $rel = new Relationship();
            $rel->project_id = $project_id;
            $rel->member_id = $member->member_id;
            $rel->role_type = 0;
            $result = $rel->save();

            if($result) {
                $item = new \stdClass();

                $item->role_type  = $rel->role_type;
                $item->account    = $member->account;
                $item->member_id  = $member->member_id;
                $item->email      = $member->email;
                $item->headimgurl = $member->headimgurl;
                $this->data['item'] = $item;

                $data = view('widget.project_member',$this->data)->render();
            }
        } else {
            $result = empty($rel) === false ? $rel->delete() : false;
        }

        return $result ? $this->jsonResult(0,$data) : $this->jsonResult(500);
    }

    /**
     * 退出项目
     * @param int $id
     * @return JsonResponse
     */
    public function quit($id)
    {
        $project_id = intval($id);
        if (empty($project_id)) {
            return $this->jsonResult(50502);
        }
        if($this->member->group_level === 2){
            return $this->jsonResult(403);
        }

        $project = Project::find($project_id);
        if (empty($project)) {
            return $this->jsonResult(40206);
        }

        $relationship = Relationship::where('project_id','=',$project_id)->where('member_id','=',$this->member_id)->first();


        //如果是项目参与者，则退出
        if(empty($relationship) === false && $relationship->role_type === 0){

            $result = $relationship->delete();
            return $result ? $this->jsonResult(0) : $this->jsonResult(500);
        }
        return $this->jsonResult(500,null,'非参与者无法退出');
    }
    /**
     * 项目转让
     * @param int $id
     * @return JsonResponse
     */
    public function transfer($id)
    {
        $project_id = intval($id);
        $account = trim($this->request->input('account'));

        if (empty($project_id)) {
            return $this->jsonResult(50502);
        }
        $project = Project::find($project_id);
        if (empty($project)) {
            return $this->jsonResult(40206);
        }

        //如果不是项目的拥有者并且不是超级管理员
        if (!Project::isOwner($project_id,$this->member->member_id) && $this->member->group_level != 0) {
            return $this->jsonResult(40305);
        }

        $member = Member::findNormalMemberOfFirst([['account', '=', $account]]);

        if (empty($member)) {
            return $this->jsonResult(40506);
        }

        //将拥有用户降级为参与者
        $rel = Relationship::where('project_id', '=', $project_id)->where('role_type', '=', 1)->first();

        $rel->role_type = 0;

        if(!$rel->save()){
            return $this->jsonResult(40802);
        }
        //如果目标用户存在则升级为拥有者
        $newRel = Relationship::where('project_id', '=', $project_id)->where('member_id', '=', $member->member_id)->first();
        if(empty($newRel)){
            $newRel = new Relationship();
        }

        $newRel->project_id = $project_id;
        $newRel->member_id = $member->member_id;
        $newRel->role_type = 1;
        if(!$newRel->save()){
            return $this->jsonResult(40802);
        }
        return $this->jsonResult(0);

    }
}