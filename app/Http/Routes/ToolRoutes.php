<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/10 0010
 * Time: 10:31
 */

namespace SmartWiki\Http\Routes;

use Illuminate\Contracts\Routing\Registrar;

class ToolRoutes
{
    public function map(Registrar $router)
    {
        //工具路由
        $router->group(['middleware' => 'authorize','prefix' => 'tool'],function ()use(&$router){
            $router->match(['GET','POST'],'{runapi}',[
                'uses' => 'RunApiController@index'
            ])->where('runapi','runapi')->name('tool.runApi');
        });
    }
}