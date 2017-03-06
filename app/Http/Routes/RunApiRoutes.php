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
                'uses' => 'RunApiController@index', 'as' => 'runapi.index'
            ]);

            $router->match(['GET','POST'],'runapi/classify/edit/{classifyId?}',[
                'uses' => 'RunApiController@editClassify', 'as' => 'runapi.edit.classify'
            ]);

            $router->post('runapi/classify/delete',[
                'uses' => 'RunApiController@deleteClassify', 'as' => 'runapi.delete.classify'
            ]);

            $router->get('runapi/classify/list/{parentId?}',[
               'uses' => 'RunApiController@getClassifyList', 'as' => 'runapi.classify.list'
            ]);

            $router->get('runapi/classify/tree',[
                'uses' => 'RunApiController@getClassifyTreeList', 'as' => 'runapi.classify.tree'
            ]);

            $router->match(['GET','POST'],'runapi/api/save/{apiId?}',[
               'uses' => 'RunApiController@editApi', 'as' => 'runapi.edit.api'
            ]);
            $router->match(['GET','POST'],'runapi/api/share/{id?}',[
                'uses' => 'RunApiController@shareRequestFolder', 'as' => 'runapi.share.api'
            ]);

            $router->get('runapi/api/metadata/get/{apiId?}',[
               'uses' =>  'RunApiController@getApiMetaData', 'as' => 'runapi.metadata.api'
            ]);

            $router->post('runapi/api/metadata/save',[
                'uses' => 'RunApiController@saveApiMetaData', 'as' => 'runapi.metadata.save.api'
            ]);

            $router->post('runapi/api/delete',[
                'uses' => 'RunApiController@deleteApi', 'as' => 'runapi.delete.api'
            ]);

            $router->post('runapi/markdown',[
                'uses' => 'RunApiController@makeMarkdown', 'as' => 'runapi.markdown'
            ]);

        });
    }
}