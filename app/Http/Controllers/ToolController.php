<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/10 0010
 * Time: 9:55
 */

namespace SmartWiki\Http\Controllers;


class ToolController extends Controller
{
    public function runApi()
    {
        return view('tool.runapi',$this->data);
    }
}