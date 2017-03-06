<?php

namespace SmartWiki\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SmartWiki\Models\Member;
use SmartWiki\Models\RequestShare;
use SmartWiki\Models\RequestFolder;
use SmartWiki\Models\RequestModel;
use SmartWiki\Models\ModelBase;

class RunApiController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        if($this->member->group_level === 2){
            abort(403);
        }
    }

    public function index()
    {
        $classifyList = RequestFolder::getApiClassifyList($this->member_id,0);

        $this->data['classify'] = [];

        if(empty($classifyList) === false && count($classifyList) > 0){
            $this->data['classify'] = $classifyList;
        }

        return view('runapi.runapi',$this->data);
    }

    /**
     * 获取接口分类列表
     * @param int $parentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassifyList($parentId = 0)
    {
        $parentId = intval($parentId);
        $containApis =  boolval($this->request->get('containApi',1));
        $dataType = $this->request->get('type','html');
        $role = RequestFolder::getRequestFolderRole($this->member_id,$parentId);

        $view = '';

        //查询子分类
        $classifyList = RequestFolder::getApiClassifyList($this->member_id,$parentId);
        if(empty($classifyList) === false && count($classifyList) > 0 && strcasecmp($dataType,'html') === 0){
            foreach ($classifyList as $classify){
                $data = (array)$classify;
                $data['role'] = $role;
                $view .= view('runapi.classify',$data)->render();
            }
        }


        $apiView = '';
        if($containApis) {
            if ($parentId > 0) {
                $apiList = RequestModel::where('classify_id', '=', $parentId)->orderBy('sort', 'DESC')->get(['api_id', 'api_name', 'method']);

                if (empty($apiList) === false && count($apiList) > 0 && strcasecmp($dataType,'html') === 0) {
                    foreach ($apiList as $item) {
                        $data = $item->toArray();
                        $data['role'] = $role;
                        $apiView .= view('runapi.api', $data)->render();
                    }
                }
            }
        }
        $data = [];
        if(strcasecmp($dataType,'html') === 0) {
            $data['view'] = $view;
            $data['api_view'] = $apiView;
        }elseif (strcasecmp($dataType,'json') === 0) {
            $data['classify'] = $classifyList;
            $data['apis'] = isset($apiList)?$apiList:null;
        }

        return $this->jsonResult(0,$data);

    }

    /**
     * 获取分类列表的树状结构
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassifyTreeList()
    {
        $classifyList = RequestFolder::getApiClassifyAllList($this->member_id);

        $view = '<ul>';
        foreach ($classifyList as $classify) {
            if($classify->parent_id === 0) {
                $view .= '<li><a href="###" data-value="'. $classify->classify_id .'"> ' . $classify->classify_name . '</a>';

                $subView = '';
                foreach ($classifyList as $item){
                    if($item->parent_id == $classify->classify_id){
                        $subView .= '<li><a href="###" data-value="'. $item->classify_id.'">'. $item->classify_name.'</a></li>';
                    }
                }
                if(empty($subView) === false) {
                    $view .= '<ul>' . $subView . '</ul>';
                }
                $view .= '</li>';
            }
        }
        $view .= '</ul>';
        $data['view'] = $view;

        return $this->jsonResult(0,$data);
    }

    /**
     * 编辑和添加接口
     * @param int $apiId
     * @return JsonResponse
     */
    public function editApi($apiId = 0)
    {
        $apiId = intval($apiId);
        $role = false;

        if($apiId > 0){
            $apiModel = RequestModel::find($apiId);
            if(empty($apiModel) || ($role = RequestFolder::getRequestFolderRole($this->member_id,$apiModel->classify_id) ) === false){
                return $this->jsonResult(403);
            }
        }
        if($this->isPost()){

            $apiId = intval($this->request->get('apiId',0));
            $api_name = $this->request->get('apiName',null);
            $description = $this->request->get('apiDescription',null);
            $classify_id = intval($this->request->get('classifyId',0));

            $request_url = $this->request->get('request_url');

            $http_method = strtoupper($this->request->get('http_method','GET'));
            $parameterType = strtolower($this->request->get('parameterType','x-www-form-urlencodeed'));
            $http_header = $this->request->get('http_header');
            $http_body = $this->request->get('http_body');
            $raw_data = $this->request->get('raw_data');

            if(empty($request_url) || mb_strlen($request_url) >500) {
                return $this->jsonResult(60004);
            }
            $methods = ['GET','POST','PUT','PATCH','DELETE','COPY','HEAD','OPTIONS','LINK','UNLINK','PURGE','LOCK','UNLOCK','PROPFND','VIEW'];

            if(!in_array($http_method,$methods)){
                return $this->jsonResult(60007);
            }
            $paramTypes = ['x-www-form-urlencodeed','raw'];
            if(!in_array($parameterType,$paramTypes)){
                return $this->jsonResult(60008);
            }

            if(($apiId <=0 && empty($api_name)) || mb_strlen($api_name)> 200) {
                return $this->jsonResult(60005);
            }
            if($apiId <=0 && $classify_id <= 0) {
                return $this->jsonResult(60006);
            }

            if($apiId <=0){
                $apiModel = new RequestModel();

            }else {
                $apiModel = RequestModel::find($apiId);
                if(empty($apiModel)){
                    return $this->jsonResult(404);
                }
                if(RequestFolder::getRequestFolderRole($this->member_id,$apiModel->classify_id) === false){
                    return $this->jsonResult(403);
                }
            }

            //检查分类操作的权限
            if($classify_id > 0) {
                $classify = RequestFolder::find($classify_id);

                if(empty($classify)){
                    return $this->jsonResult(60006);
                }
                $classifyRole = RequestFolder::getRequestFolderRole($this->member_id,$classify_id);
                if($classifyRole === false){
                    return $this->jsonResult(403);
                }
                $apiModel->classify_id = $classify_id;
            }
            if(empty($api_name) === false){
                $apiModel->api_name = $api_name;
            }
           $apiModel->request_url = $request_url;

            $apiModel->description = $description;
            $apiModel->method = $http_method;
            $apiModel->enctype = $parameterType;

            $apiModel->body = empty($http_body) ? null : json_encode($http_body);
            $apiModel->raw_data = empty($raw_data) ? null : trim($raw_data);
            $apiModel->headers = empty($http_header)?  null :json_encode($http_header);
            $apiModel->create_at = $this->member_id;

            if($apiModel->save()){
                $data['api_id'] = $apiModel->api_id;
                $data['classify_id'] = $apiModel->classify_id;
                $apiData = $apiModel->toArray();
                $apiData['role'] = RequestFolder::getRequestFolderRole($this->member_id,$apiModel->classify_id);

                $data['view'] = view('runapi.api', $apiData)->render();
                $data['api_name'] = $apiModel->api_name;
                $data['description'] = $apiModel->description;

                return $this->jsonResult(0,$data);
            }
            return $this->jsonResult(500);
        }

        if(empty($apiModel)){
            return $this->jsonResult(404);
        }

        $data = $apiModel->toArray();

        $body = $data['body'];
        $header = $data['headers'];

        unset($data['create_at']);
        unset($data['create_time']);
        unset($data['body']);

        $data['headers'] = json_decode($header,true);
        $data['body'] = json_decode($body,true);
        $data['role'] = $role;

        $requestFolder = RequestFolder::find($apiModel->classify_id);

        $data['classify_name'] = $requestFolder->classify_name;


        $isView = $this->request->get('dataType');

        if(strcasecmp($isView,'html') === 0){
            $data['view'] = view('runapi.body',$data)->render();
        }

        return $this->jsonResult(0,$data);
    }

    /**
     * 删除接口
     * @param int $apiId
     * @return JsonResponse|RequestModel
     */
    public function deleteApi($apiId = 0)
    {
        $apiId = intval($apiId);
        if ($apiId <= 0) {
            $apiId = intval($this->request->get('api_id', 0));
        }

        $model = RequestModel::find($apiId);

        if (empty($model)) {
            return $this->jsonResult(404);
        }
        //如果当前用户不是所有者则禁止删除
        if (RequestFolder::getRequestFolderRole($this->member_id, $model->classify_id) !== 0) {
            return $this->jsonResult(403);
        }

        if ($model->delete()) {
            return $this->jsonResult(0);
        }
        return $this->jsonResult(500);
    }

    /**
     * 获取接口的简单信息
     * @param $apiId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function getApiMetaData($apiId)
    {
        $apiId = intval($apiId);

        $apiModel = RequestModel::find($apiId);

        if (empty($apiModel)) {
            abort(404);
        }
        $role = RequestFolder::getRequestFolderRole($this->member_id, $apiModel->classify_id);

        if ($role === false) {
            abort(403);
        }

        $requestFolder = RequestFolder::find($apiModel->classify_id);

        $data = $apiModel->toArray();
        $data['classify_name'] = $requestFolder->classify_name;

        $data['isForm'] = true;

        return view('runapi.metadata', $data);

    }

    /**
     * 保存接口的元数据
     * @return JsonResponse
     */
    public function saveApiMetaData()
    {
        $apiId = intval($this->request->get('apiId'));
        $apiName = $this->request->get('apiName');
        $apiDescription = $this->request->get('apiDescription');
        $classifyId = intval($this->request->get('classifyId'));


        $apiModel = RequestModel::find($apiId);

        if(empty($apiModel)){
            return $this->jsonResult(404);
        }

        $role = RequestFolder::getRequestFolderRole($this->member_id,$apiModel->classify_id);

        if($role === false){
            return $this->jsonResult(403);
        }

        $role = RequestFolder::getRequestFolderRole($this->member_id,$classifyId);

        if($role === false){
            return $this->jsonResult(403);
        }


        $apiModel->api_name = $apiName;
        $apiModel->description = $apiDescription;
        $apiModel->classify_id = $classifyId;

        if($apiModel->save()){
            $data['api_id'] = $apiModel->api_id;
            $data['api_name'] = $apiModel->api_name;
            $data['classify_id'] = $apiModel->classify_id;
            $data['method'] = $apiModel->method;

            $data['role'] = $role;
            $data['view'] = view('runapi.api',$data)->render();

            return $this->jsonResult(0,$data);
        }
        return $this->jsonResult(500);
    }

    /**
     * 添加或编辑分类
     * @param int $classifyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editClassify($classifyId = 0)
    {
        $classifyId = intval($classifyId);

        if($classifyId <=0 ){
            $classifyId = intval($this->request->get('classifyId',0));
        }
        if($classifyId > 0){
            //判断是否有编辑权限
            if(RequestFolder::getRequestFolderRole($this->member_id,$classifyId) !== 0){
                return $this->jsonResult(60001);
            }
            $classify = RequestFolder::find($classifyId);
        }

        if($this->isPost()){
            $classifyName = $this->request->get('classifyName');
            $description = $this->request->get('description');
            $classifySort = $this->request->get('sort',null);
            $parentId = intval($this->request->get('parentId',0));

            if(empty($classifyName)){
                return $this->jsonResult(60002);
            }
            if(mb_strlen($classifyName) > 50){
                return $this->jsonResult(60003);
            }
            if($parentId > 0){
                $role = RequestFolder::getRequestFolderRole($this->member_id,$parentId);
                if($role !== 0){
                    return $this->jsonResult(403);
                }
            }

            //如果是创建
            if(empty($classify)){
                $classify = new RequestFolder();
                $classify->parent_id = $parentId;
                $classify->member_id = $this->member_id;
            }
            $classify->classify_name = $classifyName;
            $classify->description = $description;
            $classify->classify_sort = intval($classifySort?:$classifyId);

            $result = $classify->save();

            if($result){
                $array = $classify->toArray();
                $array['role'] = 0;

                $data['view'] = view('runapi.classify',$array)->render();

                $data['classify_id'] = $classify->classify_id;
                $data['parent_id'] = $classify->parent_id;
                $data['is_edit'] = boolval($classifyId);

                return $this->jsonResult(0,$data);
            }
            return $this->jsonResult(500);
        }

        if(empty($classify)){
            return $this->jsonResult(404);
        }
        return $this->jsonResult(0,$classify);
    }

    /**
     * 删除分类
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteClassify()
    {

        $classifyId = intval($this->request->get('classifyId',0));

        if($classifyId <= 0){
            return $this->jsonResult(50502);
        }
        //如果不是管理员，则没有删除权限
        if(RequestFolder::getRequestFolderRole($this->member_id,$classifyId) !== 0){
            return $this->jsonResult(60001);
        }
        $classify = RequestFolder::find($classifyId);

        if($classify->delete()){
            return $this->jsonResult(0);
        }else{
            return $this->jsonResult(500);
        }
    }

    /**
     * @param $apiId
     * @return RequestModel| JsonResponse
     */
    protected function isAbleEditApi($apiId)
    {
        if($apiId <= 0){
            return $this->jsonResult(404);
        }

        $apiModel= RequestModel::find($apiId);

        if(RequestFolder::getRequestFolderRole($this->member_id,$apiModel->classify_id) === false){
            return $this->jsonResult(403);
        }
        return $apiModel;
    }

    /**
     * 生成 Markdown 文档
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function makeMarkdown()
    {
        $api_name = $this->request->get('apiName',null);
        $description = $this->request->get('apiDescription',null);
        $classify_id = intval($this->request->get('classifyId',0));

        $request_url = $this->request->get('request_url');

        $http_method = strtoupper($this->request->get('http_method','GET'));
        $parameterType = strtolower($this->request->get('parameterType','x-www-form-urlencodeed'));
        $http_header = $this->request->get('http_header');
        $http_body = $this->request->get('http_body');
        $raw_data = $this->request->get('raw_data');
        $response = $this->request->get('response','');

        $data['api_name'] = $api_name;
        $data['description'] = $description;
        $data['request_host'] = '';
        $data['method'] = $http_method;
        $data['request_path'] = '';
        $data['headers'] = $http_header;
        $data['enctype'] = $parameterType;
        $data['body'] = $http_body;
        $data['raw_data'] = $raw_data;
        $data['response'] = $response;
        $data['response_error'] = '';

        if(empty($request_url) === false &&  $result = parse_url($request_url)){
            $data['request_host'] = $result['scheme'] .'://'. $result['host'];
            $data['request_path'] = substr($request_url,strlen($data['request_host']));
        }

        return view('template.markdown',$data);
    }


    public function shareRequestFolder($id = 0)
    {
        $folderId = intval($id);

        if($this->isPost()){
            $folderId = intval($this->request->get('classify_id'));
            $action = $this->request->get('action');

            $account = $this->request->get('account');

            if(!RequestFolder::isHasEditRole($this->member_id,$folderId)){
                return $this->jsonResult(404);
            }
            $requestFolder = RequestFolder::find($folderId);
            if(empty($requestFolder)){
                return $this->jsonResult(403);
            }
            $requestShare = RequestShare::where('classify_id','=',$folderId)->where('member_id','=',$this->member_id)->first();

            if($requestShare->role !== 0){
                return $this->jsonResult(403);
            }
            $member = Member::where('account','=',$account)->first();
            if(empty($member)){
                return $this->jsonResult(40513);
            }

            $model = RequestShare::where('classify_id','=',$folderId)->where('member_id','=',$member->member_id)->first();

            if(empty($model) === false){
                if(strcasecmp('del',$action) === 0){
                    if($model->delete()){
                        return $this->jsonResult(0);
                    }else{
                        return $this->jsonResult(500);
                    }
                }
                return $this->jsonResult(0);
            }
            $model = new RequestShare();
            $model->classify_id = $folderId;
            $model->member_id = $member->member_id;
            $model->role = 1;
            $model->create_time = date('Y-m-d H:i:s');

            if($model -> save()){
                $array = $model->toArray();
                $array['account'] = $member->account;

                $data['view'] = view('runapi.shareitem',$array)->render();
                return $this->jsonResult(0,$data);
            }
            return $this->jsonResult(500);
        }

        if(!RequestFolder::isHasEditRole($this->member_id,$folderId)){
            return "";
        }
        $requestFolder = RequestFolder::find($folderId);
        if(empty($requestFolder)){
            $data['errcode'] = '403';
            $data['message'] = '没有共享目录的权限';
            return view('runapi.share',$data);
        }
        $requestShare = RequestShare::where('classify_id','=',$folderId)->where('member_id','=',$this->member_id)->first();

        if($requestShare->role !== 0){
            $data['errcode'] = '403';
            $data['message'] = '没有共享目录的权限';
            return view('runapi.share',$data);
        }

        $requestShareList = RequestShare::getRequestMembers($folderId);


        $data['lists'] = $requestShareList;
        $data['classify_id'] = $folderId;

        return view("runapi.share",$data);
    }
}