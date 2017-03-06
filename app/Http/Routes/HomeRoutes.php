<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/10 0010
 * Time: 10:23
 */

namespace SmartWiki\Http\Routes;


use Illuminate\Contracts\Routing\Registrar;

class HomeRoutes
{
    public function map(Registrar $router)
    {
        //检查项目权限
        $router->post('/check_document_auth',[
            'as' => 'home.check_document_auth' , 'uses' => 'HomeController@checkDocumentAuth'
        ]);
        //用户登录
        $router->match(['get','post'],'/login',[
            'uses' => 'AccountController@login' , 'as' => 'account.login'
        ]);

        //退出登录

        $router->get('/logout',[
            'as' => 'account.logout', 'uses' => 'AccountController@logout'
        ]);

        //用户注册
        $router->match(['get','post'],'/register',[
            'uses' => 'AccountController@register', 'as' => 'account.register'
        ]);

        //找回密码
        $router->match(['get','post'],'/find_password',[
            'uses' => 'AccountController@findPassword', 'as' => 'account.find_password'
        ]);


        //找回密码后的修改
        $router->match(['get','post'],'/modify_password/{key}',[
            'uses' => 'AccountController@modifyPassword' ,'as' => 'account.modify_password'
        ])->where('key','^([a-fA-F0-9]{32})$');

        //处理结果跳转页
        $router->get('/process_result')->name('account.process_result')->uses('AccountController@processResult');

        //验证码
        $router->get('/verify',[
            'as' => 'captcha.verify', 'uses' => 'CaptchaController@verify'
        ]);

        $router->get('/qrcode',[
            'as' => 'qrcode.index', 'uses' => 'QrCodeController@index'
        ]);

        //发送邮件
        $router->post('/send_mail',['uses' => 'MailController@sendMail'])->name('mail.send_mail');

        //首页
        $router->get('/',[
            'as' => 'home.index', 'uses' => 'HomeController@index'
        ]);

        //搜索
        $router->get('/search',[
            'uses' => 'SearchController@search'
        ])->name('search.search');

        //查看文档
        $router->get('/show/{id}',[
            'as' => 'home.show', 'uses' => 'HomeController@show'
        ]);
    }
}