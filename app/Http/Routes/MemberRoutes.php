<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/10 0010
 * Time: 10:29
 */

namespace SmartWiki\Http\Routes;

use Illuminate\Contracts\Routing\Registrar;

class MemberRoutes
{
    public function map(Registrar $router)
    {
        /**
         * 超级管理员
         */
        $router->group(['middleware' => 'super.member','prefix' => 'member'],function ()use(&$router){
            //站点配置
            $router->get('setting',[
                'as' => 'member.setting', 'uses' => 'MemberController@setting'
            ]);
            //编辑站点配置
            $router->match(['get','post'],'setting/edit/{id?}',[
                'uses' => 'MemberController@editSetting'
            ])->name('member.setting.edit');
            //删除配置
            $router->match(['get','post'],'setting/delete/{id?}',[
                'uses' => 'MemberController@deleteSetting'
            ])->name('member.setting.delete');
            //用户管理
            $router->get('users',[
                'as' => 'member.users', 'uses' => 'MemberController@users'
            ]);
            //编辑用户
            $router->match(['get','post'],'edit/{id?}',[
                'uses' => 'MemberController@editUser'
            ])->name('member.users.edit');
            //禁用或启用用户
            $router->post('users/delete/{id?}',[
                'uses' => 'MemberController@deleteUser'
            ])->name('member.users.delete');

        });
    }
}