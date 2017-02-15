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

    public function editApi($apiId = 0)
    {
        $apiId = intval($apiId);

        if($apiId > 0){

        }
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
                $classify->parent_id = $this->request->get('parentId',0);
                $classify->member_id = $this->member_id;
            }
            $classify->classify_name = $classifyName;
            $classify->description = $description;
            $classify->classify_sort = $classifySort?:$classifyId;

            $result = $classify->save();

            if($result){

                $this->data = view('runapi.classify_top',$classify)->render();
                return $this->jsonResult(0,$this->data);
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
     * @param $classifyId
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