<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/29 0029
 * Time: 11:28
 */

namespace SmartWiki\Http\Controllers;

use Mail;

/**
 * 邮件相关方法
 * Class MailController
 * @package SmartWiki\Http\Controllers
 */
class MailController extends Controller
{

    public function sendMail()
    {
        Mail::send('emails.welcome', ['key' => 'value'], function($message)
        {
            $message->to('lifei6671@163.com', 'John Smith')->subject('Welcome!');
        });
    }
}