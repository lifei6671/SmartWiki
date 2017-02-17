<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/14 0014
 * Time: 17:01
 */

namespace SmartWiki\Http\Controllers;

use SmartWiki\Models\ApiClassify;
use SmartWiki\Models\ApiModel;
use SmartWiki\Models\ApiShare;

class RunApiController extends Controller
{
    public function index()
    {
        $classifyList = ApiClassify::getApiClassifyList($this->member_id,0);

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

        $view = '';

        //查询子分类
        $classifyList = ApiClassify::getApiClassifyList($this->member_id,$parentId);
        if(empty($classifyList) === false && count($classifyList) > 0 && strcasecmp($dataType,'html') === 0){
            foreach ($classifyList as $classify){
                $view .= view('runapi.classify',(array)$classify)->render();
            }
        }


        $apiView = '';
        if($containApis) {
            if ($parentId > 0) {
                $apiList = ApiModel::where('classify_id', '=', $parentId)->orderBy('sort', 'DESC')->get(['api_id', 'api_name', 'method']);

                if (empty($apiList) === false && count($apiList) > 0 && strcasecmp($dataType,'html') === 0) {
                    foreach ($apiList as $item) {

                        $apiView .= view('runapi.api', $item->toArray())->render();
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
        $classifyList = ApiClassify::getApiClassifyAllList($this->member_id);

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

    public function editApi($apiId = 0)
    {
        $apiId = intval($apiId);

        if($apiId > 0){
            $apiModel = ApiModel::find($apiId);
            if(empty($apiModel) || !ApiClassify::isHasEditRole($this->member_id,$apiModel->classify_id)){
                return $this->jsonResult(403);
            }
        }
        if($this->isPost()){

            $apiId = intval($this->request->get('api_id',0));
            $request_url = $this->request->get('request_url');
            $api_name = $this->request->get('api_name',null);
            $classify_id = intval($this->request->get('classify_id',0));
            $description = $this->request->get('description',null);
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
                $apiModel = new ApiModel();

            }else {
                $apiModel = ApiModel::find($apiId);
                if(!ApiClassify::isHasEditRole($this->member_id,$apiModel->classify_id)){
                    return $this->jsonResult(403);
                }
            }

            //检查分类操作的权限
            if($classify_id > 0) {
                $classify = ApiClassify::find($classify_id);

                if(empty($classify)){
                    return $this->jsonResult(60006);
                }
                $classifyRole = ApiShare::where('member_id','=',$this->member_id)->where("classify_id",'=',$classify_id)->first();
                if(empty($classifyRole)){
                    return $this->jsonResult(60001);
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
            $apiModel->body = json_encode(['raw' => $raw_data,'x-www-form-urlencodeed' => $http_body]);
            $apiModel->headers = json_encode($http_header);
            $apiModel->create_at = $this->member_id;

            if($apiModel->save()){
                $data['api_id'] = $apiModel->api_id;
                $data['classify_id'] = $apiModel->classify_id;
                $data['view'] = view('runapi.api', $apiModel->toArray())->render();
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

        unset($data['create_at']);
        unset($data['create_time']);


        return $this->jsonResult(0,$data);
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
            if(!ApiClassify::isHasEditRole($this->member_id,$classifyId)){
                return $this->jsonResult(60001);
            }
            $classify = ApiClassify::find($classifyId);
        }

        if($this->isPost()){
            $classifyName = $this->request->get('classifyName');
            $description = $this->request->get('description');
            $classifySort = $this->request->get('sort',null);

            if(empty($classifyName)){
                return $this->jsonResult(60002);
            }
            if(mb_strlen($classifyName) > 50){
                return $this->jsonResult(60003);
            }

            //如果是创建
            if(empty($classify)){
                $classify = new ApiClassify();
                $classify->parent_id = intval($this->request->get('parentId',0));
                $classify->member_id = $this->member_id;
            }
            $classify->classify_name = $classifyName;
            $classify->description = $description;
            $classify->classify_sort = intval($classifySort?:$classifyId);

            $result = $classify->save();

            if($result){

                $data['view'] = view('runapi.classify',$classify)->render();
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
        if(!ApiClassify::isHasEditRole($this->member_id,$classifyId)){
            return $this->jsonResult(60001);
        }
        $classify = ApiClassify::find($classifyId);

        if($classify->delete()){
            return $this->jsonResult(0);
        }else{
            return $this->jsonResult(500);
        }
    }
}