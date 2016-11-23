<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//用户登录
Route::match(['get','post'],'/login',[
    'uses' => 'AccountController@login'
])->name('account.login');
//退出登录
Route::get('/logout',[
    'as' => 'account.logout', 'uses' => 'AccountController@logout'
]);
//验证码
Route::get('/verify',[
    'as' => 'captcha.verify', 'uses' => 'CaptchaController@verify'
]);
Route::get('/',[
    'middleware' => 'authorize', 'as' => 'home.index', 'uses' => 'HomeController@index'
]);
//查看文档
Route::get('/show/{id}',[
    'as' => 'home.show', 'uses' => 'HomeController@show'
]);
Route::post('/check_document_auth',[
    'as' => 'home.check_document_auth' , 'uses' => 'HomeController@checkDocumentAuth'
]);
/**
 * 超级管理员
 */
Route::group(['middleware' => 'super.member','prefix' => 'member'],function (){
    //站点配置
    Route::get('setting',[
        'as' => 'member.setting', 'uses' => 'MemberController@setting'
    ]);
    //编辑站点配置
    Route::match(['get','post'],'setting/edit/{id?}',[
        'uses' => 'MemberController@editSetting'
    ])->name('member.setting.edit');
    Route::match(['get','post'],'setting/delete/{id?}',[
        'uses' => 'MemberController@deleteSetting'
    ])->name('member.setting.delete');
    //用户管理
    Route::get('users',[
        'as' => 'member.users', 'uses' => 'MemberController@users'
    ]);
    //编辑用户
    Route::match(['get','post'],'edit/{id?}',[
        'uses' => 'MemberController@editUser'
    ])->name('member.users.edit');
    //禁用或启用用户
    Route::post('users/delete/{id?}',[
        'uses' => 'MemberController@deleteUser'
    ])->name('member.users.delete');
});

Route::group(['middleware' => 'authorize','prefix' => 'project'],function (){
    //创建项目
    Route::match(['get','post'],'create',[
        'uses' => 'ProjectController@create'
    ])->name('project.create');

    //编辑项目
    Route::match(['get','post'],'edit/{id?}',[
        'uses' => 'ProjectController@edit'
    ])->name('project.edit');
    //删除项目
    Route::match(['get','post'],'delete/{id}',[
        'uses' => 'ProjectController@delete'
    ])->name('project.delete');
    //成员管理
    Route::get('members/{id}',[
        'as' => 'project.members', 'uses' => 'ProjectController@members'
    ]);
    //添加或删除项目的用户
    Route::post('members/add/{id}',[
        'as' => 'project.members.add', 'uses' => 'ProjectController@addMember'
    ]);
});

Route::group(['middleware' => 'authorize','prefix' => 'docs'],function (){
    //文档编辑首页
    Route::get('{id}',[
        'as' => 'document.index', 'uses' => 'DocumentController@index'
    ])->where('id', '[0-9]+');

    //编辑文档
    Route::get('edit/{id}',[
        'as' => 'document.edit' , 'uses' => 'DocumentController@edit'
    ])->where('id', '[0-9]+');
    //保存文档
    Route::post('save',[
       'as' => 'document.save', 'uses' => 'DocumentController@save'
    ]);
    //删除文档
    Route::post('delete/{doc_id}',[
       'as' => 'document.delete', 'uses' => 'DocumentController@delete'
    ])->where('doc_id', '[0-9]+');

    Route::post('sort/{doc_id}',[
        'as' => 'document.sort', 'uses' => 'DocumentController@sort'
    ])->where('doc_id', '[0-9]+');

    //查看文档记录
    Route::match(['get','post'],'history/{id}',[
        'uses' => 'DocumentController@history'
    ])->where('id', '[0-9]+')->name('document.history');

    //删除文档记录
    Route::post('history/delete',[
        'uses' => 'DocumentController@deleteHistory'
    ])->name('document.history.delete');

    //恢复文档版本
    Route::post('history/restore',[
        'uses' => 'DocumentController@restoreHistory'
    ])->name('document.history.restore');
});
//文件上传
Route::post('/upload',[
    'middleware' => 'authorize' , 'as' => 'document.upload', 'uses' => 'DocumentController@upload'
]);

//查看文档详情
Route::get('/docs/show/{doc_id}',[
    'as' => 'document.show', 'uses' => 'DocumentController@show'
]);

//文档编辑小部件
Route::get('widget/edit-document',[
    'as' => 'widget.editDocument', 'uses' => 'WidgetController@editDocument'
]);

/**
 * 用户中心
 */
Route::group(['middleware' => 'authorize','prefix' => 'member'],function (){
    //修改密码
    Route::match(['get','post'],'account',[
        'uses' => 'MemberController@account'
    ])->name('member.account');
    //我的项目列表
    Route::get('projects',[
        'as' => 'member.projects', 'uses' => 'MemberController@projects'
    ]);
    //用户中心
    Route::match(['post','get'],'',[
        'uses' => 'MemberController@index'
    ])->name('member.index');

    Route::post('upload',[
        'as' => 'member.upload', 'uses' => 'MemberController@upload'
    ]);

});
/**
 * 安装首页
 */
Route::match(['get','post'],'/install',[
    'uses' => 'InstallController@index'
])->name('install.index');

Route::get('/install/next',[
    'as' => 'install.next', 'uses' => 'InstallController@next'
]);