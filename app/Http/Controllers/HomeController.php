<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/10/25
 * Time: 9:22
 */

namespace SmartWiki\Http\Controllers;


use SmartWiki\Member;
use DB;
use SmartWiki\Project;
use SmartWiki\Relationship;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends Controller
{
    const PageSize = 20;


    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pageIndex = intval($this->request->input('page',1));
        $member_id = null;

        if(empty($this->member) === false){
            $member_id = $this->member->member_id;
        }

        $this->data['lists'] = Project::getProjectByMemberId($pageIndex,self::PageSize,$member_id);


        return view('home.index',$this->data);
    }

    /**
     * 显示文档
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        if($id <= 0){
            abort(404);
        }
        $project = Project::getProjectFromCache($id);

        if(empty($project)){
            abort(404);
        }

        $this->data = $project;

        $member_id = null;
        if(empty($this->member) === false){
            $member_id = $this->member->member_id;

        }
        //校验是否有权限访问文档

        if(Project::hasProjectShow($id,$member_id) === false){
            $role = session_project_role($id);
            if(empty($role)){
                return view('home.password',$this->data);
            }
        }

        $this->data['title'] = $project->project_name;

        $this->data['project'] = $project;

        $this->data['tree'] = Project::getProjectHtmlTree($id);

        $this->data['body'] = '';

        return view('home.show',$this->data);
    }

    /**
     * 检查文档访问密码是否正确
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkDocumentAuth()
    {
        $id = intval($this->request->input('pid',0));
        $passwd = $this->request->input('projectPwd');

        if($id <= 0){
            return $this->jsonResult(40301);
        }
        $member_id = empty($this->member) ? null : $this->member->member_id;

        if(Project::hasProjectShow($id,$member_id,$passwd)){
            session_project_role($id,['project_id'=>$id,'project_password' => $passwd]);
            return $this->jsonResult(0);
        }
        $project = Project::find($id);
        if(empty($project)){
            return $this->jsonResult(40301);
        }

        return $this->jsonResult(40302);
    }
}