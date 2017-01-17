<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/1/16 0016
 * Time: 14:56
 */

namespace SmartWiki\Http\Controllers;


use Request;
use SmartWiki\WikiConfig;

class SettingController extends Controller
{
    public function site()
    {

        if($this->isPost()){
            $form = Request::all();
            if(empty($form) === false){
                foreach ($form as $key=>$value){
                    WikiConfig::where('key','=',$key)->update(['value'=>$value]);
                }
            }
            return  $this->jsonResult(0,null,'ok');
        }
        $result =  WikiConfig::get()->toArray();


        if(empty($result) === false && count($result) > 0){
            $this->data = array_merge($this->data,array_column($result,'value','key'));
        }
//        var_dump(array_column($result,'value','key'));exit;

        $this->data['setting_site'] = true;
        return view('setting.site',$this->data);
    }
}