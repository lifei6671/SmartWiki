<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/21 0021
 * Time: 16:16
 */

namespace SmartWiki\Http\Controllers;

use DB;
use SmartWiki\Exceptions\DataException;
use SmartWiki\Member;
use SmartWiki\WikiConfig;
use PDO;

class InstallController extends Controller
{
    public function index()
    {
        if($this->isPost()) {
            $dataAddress = $this->request->input('dataAddress');
            $dataAccount = $this->request->input('dataAccount');
            $dataName = $this->request->input('dataName');
            $dataPassword = $this->request->input('dataPassword');
            $dataPort = $this->request->input('dataPort','3306');

            $account = $this->request->input('account');
            $password = $this->request->input('password');
            $email = $this->request->input('email');

            $matches = array();
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{4,19}$/', $account, $matches)) {
                return $this->jsonResult(40508);
            }
            if(empty($password) || strlen($password) < 6 || strlen($password) > 18){
                return $this->jsonResult(1000001,null,'管理员密码必须在6-18字符之间');
            }
            if(empty($email) ||  !filter_var ($email, FILTER_VALIDATE_EMAIL )){
                return $this->jsonResult(40503);
            }

            try {

                $sqlContent = @file_get_contents(resource_path('data/data.sql'));

                if(empty($sqlContent)){
                    return $this->jsonResult(1000002,null,'SQL文件不存在');
                }

                $pdo = new PDO('mysql:host=' . $dataAddress . ';dbname=' . $dataName . ';port='.$dataPort, $dataAccount, $dataPassword,[PDO::ATTR_AUTOCOMMIT=>0 ]);

                $pdo->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);

            }catch (\Exception $ex){
               return $this->jsonResult($ex->getCode(),null,$ex->getMessage());
            }
            try{

                if($pdo->beginTransaction()) {
                    $pdo->query('set names utf8');//设置编码

                    $sqlStr = explode(';', $sqlContent);

                    foreach ($sqlStr as $sql) {
                        if(!empty($sql)){
                            $pdo->exec($sql);
                        }
                    }
                    $sql = 'INSERT wk_member(account,member_passwd,group_level,nickname,email,create_time,state,headimgurl) 
                    VALUES (:account,:member_passwd,0,:nickname,:email,:create_time,0,:headimgurl);';


                    $params = [
                        ':account' => $account,
                        ':member_passwd' => password_hash($password, PASSWORD_DEFAULT),
                        ':nickname' => $account,
                        ':email' => $email,
                        ':create_time' => date('Y-m-d H:i:s'),
                        ':headimgurl' => '/static/images/middle.gif'];

                    $sth = $pdo->prepare($sql,[PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                    if($sth->execute($params) === false){

                        throw new DataException('添加管理员时出错',1000004);
                    }

                    $sql = 'INSERT wk_config(`name`,`key`,`value`,`config_type`,`remark`,`create_time`) VALUES(:name,:key,:value,:config_type,:remark,:create_time);';
                    $sth = $pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                    $params = [
                      ':name' => '站点名称',
                        ':key' => 'SITE_NAME',
                        ':value' => 'SmartWiki',
                        ':config_type' => 'system',
                        ':remark' => '站点名称',
                        ':create_time' => date('Y-m-d H:i:s'),
                    ];
                    if($sth->execute($params) === false){
                        throw new DataException('添加系统配置时出错',1000004);
                    }
                    $sth = $pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
                    $params = [
                        ':name' => '启用文档历史',
                        ':key' => 'ENABLED_HISTORY',
                        ':value' => '0',
                        ':config_type' => 'system',
                        ':remark' => '是否启用文档历史记录：0 否/1 是',
                        ':create_time' => date('Y-m-d H:i:s'),
                    ];
                    if($sth->execute($params) === false){

                        throw new DataException('添加系统配置时出错',1000004);
                    }

                    $pdo->commit();

                }else{
                    return $this->jsonResult(1000003,null,'执行数据库事物失败');
                }

            }catch (\Exception $ex){

                $pdo->rollBack();

                $sql = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = :dbName';

                $sth = $pdo->prepare($sql);
                $sth->execute([':dbName' => $dataName]);

                $result = $sth->fetchAll();
                if(empty($result) === false){
                    foreach ($result as $item){
                        $pdo->exec('DROP TABLE ' . $item['TABLE_NAME']);
                    }
                }

                return $this->jsonResult($ex->getCode(),null,$ex->getMessage());
            }
            $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,1);

            $params = [
                'DB_HOST' => $dataAddress,
                'DB_PORT' => $dataPort,
                'DB_DATABASE' => $dataName,
                'DB_USERNAME' => $dataAccount,
                'DB_PASSWORD' => $dataPassword
            ];
            modify_env($params);

            file_put_contents(public_path('install.lock'),'true');
            session('install.result',true);
            $url = (route('member.index'));


            return $this->jsonResult(0,["url" => $url]);

        }
        return view('install.index',$this->data);
    }

}