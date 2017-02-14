<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/10 0010
 * Time: 9:55
 */

namespace SmartWiki\Http\Controllers;


use SmartWiki\Models\ApiClassify;

class ToolController extends Controller
{
    public function runApi()
    {
        $classifyList = ApiClassify::getApiClassifyList($this->member_id,0);

        $this->data['classify'] = [];

        if(empty($classifyList) === false && count($classifyList) > 0){
            $this->data['classify'] = $classifyList;
        }
        return view('tool.runapi',$this->data);
    }


}