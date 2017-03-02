<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/15 0015
 * Time: 14:11
 */

namespace SmartWiki\Observers;


use SmartWiki\Models\RequestShare;
use SmartWiki\Models\RequestFolder;
use SmartWiki\Models\RequestModel;

class RequestFolderObservers
{
    public function creating(RequestFolder $model)
    {
        $model->api_count = 0;
    }

    public function created(RequestFolder $model)
    {
        $share = new RequestShare();
        $share->classify_id = $model->classify_id;
        $share->member_id = $model->member_id;
        $share->role = 0;
        $share->save();
    }

    public function deleted(RequestFolder $model)
    {
        RequestFolder::updateRequestCount($model->classify_id);
        RequestShare::where('classify_id','=',$model->classify_id)->delete();
        RequestModel::where('classify_id','=',$model->classify_id)->delete();

        if($model->parent_id === 0){
            $classifyList = RequestFolder::where('parent_id','=',$model->classify_id)->get();
            if(empty($classifyList) === false){
                foreach ($classifyList as $item){
                    $item->delete();
                }

            }
        }
    }
}