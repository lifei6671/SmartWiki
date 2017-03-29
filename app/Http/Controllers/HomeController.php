<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/10/25
 * Time: 9:22
 */

namespace SmartWiki\Http\Controllers;


use Illuminate\Http\Request;
use SmartWiki\Models\Document;
use SmartWiki\Models\Member;
use SmartWiki\Models\Project;

class HomeController extends Controller
{
    const PageSize = 20;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //如果没有启用匿名访问
        if(!wiki_config('ENABLE_ANONYMOUS',false) && empty($this->member)){
            return redirect(route('account.login'));
        }
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

        $member_id = null;
        if(empty($this->member) === false){
            $member_id = $this->member->member_id;

        }

        $permissions = Project::hasProjectShow($id,$member_id);

        //校验是否有权限访问文档
        if($permissions === 0){
            abort(404);
        }elseif($permissions === 2){
            $role = session_project_role($id);
            if(empty($role)){
                $this->data = $project;
                return view('home.password',$this->data);
            }
        }else if($permissions === 3 && empty($member_id)){
            return redirect(route("account.login"));
        }elseif($permissions === 3) {
            abort(403);
        }
        $member = Member::find($project->create_at);

        $this->data['author'] = '未知';
        $this->data['author_headimgurl'] = asset('static/images/middle.gif');
        $this->data['modify_time'] = $project->modify_time?:$project->create_time;
        $this->data['title'] = $project->project_name;
        $this->data['project'] = $project;
        $this->data['tree'] = Project::getProjectHtmlTree($id);
        $this->data['body'] = $project->description;
        $this->data['first_document'] = Document::where('project_id','=',$id)->where('parent_id','=',0)->orderBy('doc_sort','ASC')->limit(1)->first(['doc_id','doc_name','parent_id']);


        //查询作者信息
        if(empty($member) === false) {
            $this->data['author'] = $member->nickname?:$member->account;
            $this->data['author_headimgurl'] = $member->headimgurl ?: $member->headimgurl;
        }
        //查询最后修改时间
        $lastModifyDoc = Document::where('project_id','=',$id)->orderBy('modify_time','DESC')->first();
        if($lastModifyDoc && $lastModifyDoc->modify_time) {
            $this->data['modify_time'] = $lastModifyDoc->modify_time;
        }



        return view('home.project',$this->data);
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
        $project = Project::find($id);
        if(empty($project)){
            return $this->jsonResult(40301);
        }

        $member_id = empty($this->member) ? null : $this->member->member_id;


        if(Project::hasProjectShow($id,$member_id,$passwd) === 1){
            session_project_role($id,['project_id'=>$id,'project_password' => $passwd]);
            return $this->jsonResult(0);
        }


        return $this->jsonResult(40302);
    }
}