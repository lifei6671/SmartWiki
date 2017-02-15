<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/15 0015
 * Time: 17:13
 */

namespace SmartWiki\Http\Routes;

use Illuminate\Contracts\Routing\Registrar;

class RunApiRoutes
{
    public function map(Registrar $router)
    {
        //工具路由
        $router->group(['middleware' => 'authorize','prefix' => 'tool'],function ()use(&$router){
            $router->match(['GET','POST'],'runapi',[
                'uses' => 'RunApiController@index'
            ])->name('runapi.index');

            $router->match(['GET','POST'],'edit_classify/{classifyId?}',[
                'uses' => 'RunApiController@editClassify'
            ])->name('runapi.edit.classify');

            $router->post('runapi/delete',[
                'uses' => 'RunApiController@deleteClassify', 'as' => 'runapi.delete.classify'
            ]);
        });
    }
}