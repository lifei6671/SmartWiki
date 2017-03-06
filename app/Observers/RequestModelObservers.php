<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/3/1 0001
 * Time: 16:39
 */

namespace SmartWiki\Observers;


use SmartWiki\Models\RequestFolder;
use SmartWiki\Models\RequestModel;

class RequestModelObservers
{
    public function creating()
    {

    }
    public function created(RequestModel $model)
    {
        RequestFolder::updateRequestCount($model->classify_id);

    }

    public function deleted(RequestModel $model)
    {
        RequestFolder::updateRequestCount($model->classify_id);
    }

    public function updated(RequestModel $model)
    {
        RequestFolder::updateRequestCount($model->classify_id);
    }
}