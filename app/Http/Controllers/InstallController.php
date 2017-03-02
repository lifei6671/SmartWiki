<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/21 0021
 * Time: 16:16
 */

namespace SmartWiki\Http\Controllers;

use SmartWiki\Exceptions\DataException;
use PDO;

class InstallController extends Controller
{

    public function next()
    {
        if($this->isPost()) {
            $dbHost = $this->request->input('dataAddress');
            $dbUser = $this->request->input('dataAccount');
            $dbName = $this->request->input('dataName');
            $dbPassword = $this->request->input('dataPassword');
            $dbPort = $this->request->input('dataPort','3306');

            $account = $this->request->input('account');
            $password = $this->request->input('password');
            $email = $this->request->input('email');

            try{
                system_install($dbHost,$dbName,$dbPort,$dbUser,$dbPassword,$account,$password,$email);
            }catch (\Exception $ex){
                return $this->jsonResult($ex->getCode(),null,$ex->getMessage());
            }

            @file_put_contents(public_path('install.lock'),'true');
            session('install.result',true);
            $url = (route('member.projects'));


            return $this->jsonResult(0,["url" => $url]);

        }
        return view('install.next',$this->data);
    }

}