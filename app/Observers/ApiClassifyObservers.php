<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/15 0015
 * Time: 14:11
 */

namespace SmartWiki\Observers;


use SmartWiki\Models\ApiClassify;
use SmartWiki\Models\ApiModel;
use SmartWiki\Models\ApiShare;

class ApiClassifyObservers
{
    public function creating(ApiClassify $model)
    {
        $model->api_count = 0;
    }

    public function created(ApiClassify $model)
    {
        $classify = ApiClassify::find($model->classify_id);
        if(empty($classify) === false){
            if($classify->parent_id === 0){
                $classify->api_count = ApiClassify::where('parent_id','=',$model->classify_id)
                    ->sum('api_count');
            }else{
                $classify->api_count = ApiModel::where('classify_id','=',$model->classify_id)
                    ->count();
            }
            $classify->save();
        }

        $share = new ApiShare();
        $share->classify_id = $model->classify_id;
        $share->member_id = $model->member_id;
        $share->role = 0;
        $share->save();

    }

    public function deleted(ApiClassify $model)
    {
        ApiShare::where('classify_id','=',$model->classify_id)->delete();
        ApiModel::where('classify_id','=',$model->classify_id)->delete();

        if($model->parent_id === 0){
            $classifyList = ApiClassify::where('parent_id','=',$model->classify_id)->get();
            if(empty($classifyList) === false){
                foreach ($classifyList as $item){
                    $item->delete();
                }

            }
        }
    }
}