<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/2
 * Time: 8:51
 */

namespace SmartWiki\Http\Controllers;

use Gregwar\Captcha\CaptchaBuilder;
use Storage;
use Session;

class CaptchaController extends Controller
{
    /**
     * 显示验证码
     * @return mixed
     */
    public function verify()
    {
        header('Content-type: image/jpeg');
        $fonts = Storage::allFiles('fonts');

        $builder = new CaptchaBuilder();
        $font = storage_path('app/' . $fonts[mt_rand(0,count($fonts)-1)]);

        $builder->build(110,34,$font);

        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        Session::flash('milkcaptcha', $phrase);
        $builder->output();
        header('Content-type: image/jpeg');

    }
}