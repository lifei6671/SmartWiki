<?php

use SmartWiki\Extentions\Markdown\Parser\AutoLinkParser;
use SmartWiki\Extentions\Markdown\Parser\HttpMethodParser;
use SmartWiki\Extentions\Markdown\Renderer\HttpMethodRenderer;
use SmartWiki\Member;
use SmartWiki\WikiConfig;


if (! function_exists('session_member')) {
    /**
     * 获取或设置登录用户 Session
     * @param Member|null $member
     * @return mixed|Member
     */
    function session_member(Member $member = null){
        if($member == null){
            $member = session('member');
            if(empty($member) === false){
                $member->member_id = intval($member->member_id);
                $member->group_level = intval($member->group_level);
            }
        }else{
            session(['member' => $member]);
        }

        return $member;
    }
}

if(!function_exists('cookie_member')){
    /**
     * 获取或设置登录用户 Cookie
     * @param Member|null $member
     * @param bool $isExpired
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|Member|Member[]
     */
    function cookie_member(Member $member = null, $isExpired = false){
        if($isExpired){
            $cookie = cookie('login_token', '', 60 * 24 * 30*-1);
            Cookie::queue($cookie);
        }else {
            if ($member == null) {
                $cookie = Request::cookie('login_token');
                if (empty($cookie) === false) {
                    $member = Member::find($cookie['member_id']);
                }
            } else {
                $data = ['member_id' => $member->member_id, 'unique' => uniqid(), 'last_login_time' => time(), 'user_agent' => Request::header('User-Agent')];

                $cookie = cookie('login_token', $data, 60 * 24 * 30);
                Cookie::queue($cookie);
            }
        }
        return $member;
    }
}

if(!function_exists('session_project_role')){
    /**
     * 获取或设置项目访问权限
     * @param int $project_id
     * @param null $value
     * @return mixed
     */
    function session_project_role($project_id,$value = null){
        $key = 'project.role.'. $project_id;
        if(empty($value)){
            return session($key);
        }else{
             Session::put([$key => $value]);
            return session($key);
        }
    }
}

if(!function_exists('wiki_config')){
    /**
     * 获取指定键名的值如果不存在则设置默认值
     * @param string $key
     * @param null $default
     * @return mixed|null|string
     */
    function wiki_config($key,$default = null){
        $config = WikiConfig::getConfigFromCache($key);

        return empty($config) ? $default : $config->value;
    }
}

if(!function_exists('modify_env')) {

    /**
     * 修改ENV文件
     * @param array $data
     */
    function modify_env(array $data)
    {
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

        if(!file_exists($envPath)){
            @copy(base_path() . DIRECTORY_SEPARATOR . '.env.example', base_path() . DIRECTORY_SEPARATOR . '.env');
        }
        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

        $contentArray->transform(function ($item) use ($data) {
            foreach ($data as $key => $value) {
                if (str_contains($item, $key)) {
                    return $key . '=' . $value;
                }
            }

            return $item;
        });

        $content = implode($contentArray->toArray(), "\n");

        file_put_contents($envPath, $content, 0);
    }
}

if(!function_exists('is_can_create_project')) {
    /**
     * 判断指定用户是否能创建用户
     * @param int $member_id
     * @return bool
     */
    function is_can_create_project($member_id){
        return \SmartWiki\Project::isCanCreateProject($member_id);
    }
}

if(!function_exists('wiki_version')) {
    /**
     * 获取系统版本
     * @return mixed
     */
    function wiki_version(){
        return env('APP_VERSION',null);
    }
}

if(!function_exists('system_install')) {
    /**
     * @param string $dbHost 数据库地址
     * @param string $dbName 数据库名称
     * @param int $dbPort 端口号
     * @param string $dbUser 数据库账号
     * @param string $dbPassword 数据库密码
     * @param string $account 管理员账号
     * @param string $password 管理员密码
     * @param string $email 管理员邮箱
     * @return bool 是否成功
     * @throws Exception
     */
    function system_install($dbHost,$dbName,$dbPort,$dbUser,$dbPassword, $account, $password, $email)
    {

        $matches = array();
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{4,19}$/', $account, $matches)) {
            throw new \Exception('管理员账号必须在4-19字符之间',40508);
        }
        if (empty($password) || strlen($password) < 6 || strlen($password) > 18) {
            throw new \Exception('管理员密码必须在6-18字符之间',1000001);
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('邮箱地址格式不正确',40503);
        }

        $sqlContent = @file_get_contents(resource_path('data/data.sql'));

        if (empty($sqlContent)) {
            throw new \Exception('SQL文件不存在',1000002);
        }

        $pdo = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName . ';port=' . $dbPort, $dbUser, $dbPassword, [PDO::ATTR_AUTOCOMMIT => 0]);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        try {

            if ($pdo->beginTransaction()) {
                $pdo->query('set names utf8');//设置编码

                $pdo->exec($sqlContent);


                $sql = 'INSERT wk_member(account,member_passwd,group_level,nickname,email,create_time,state,headimgurl) 
                    VALUES (:account,:member_passwd,0,:nickname,:email,:create_time,0,:headimgurl);';


                $params = [
                    ':account' => $account,
                    ':member_passwd' => password_hash($password, PASSWORD_DEFAULT),
                    ':nickname' => $account,
                    ':email' => $email,
                    ':create_time' => date('Y-m-d H:i:s'),
                    ':headimgurl' => '/static/images/middle.gif'];

                $sth = $pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

                if ($sth->execute($params) === false) {

                    throw new \Exception('添加管理员时出错', 1000004);
                }

                $pdo->commit();

            } else {
                throw new \Exception('执行数据库事物失败', 1000003);
            }

        } catch (\Exception $ex) {

            $pdo->rollBack();

            throw $ex;
        }
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);



        $params = [
            'APP_DEBUG' => 'false',
            'DB_HOST' => $dbHost,
            'DB_PORT' => $dbPort,
            'DB_DATABASE' => $dbName,
            'DB_USERNAME' => $dbUser,
            'DB_PASSWORD' => $dbPassword
        ];
        if(isset($_SERVER['REQUEST_SCHEME']) && isset($_SERVER['HTTP_HOST'])){
            $url = $_SERVER['REQUEST_SCHEME'] .'://' . $_SERVER['HTTP_HOST'];
            $params['APP_URL'] = $url;
        }
        modify_env($params);

        file_put_contents(public_path('install.lock'), 'true');
        return true;

    }
}

if(!function_exists('markdown_converter')) {
    /**
     * 解析 markdown 字符串
     * @param $text
     * @return string
     */
    function markdown_converter($text){

        $environment = League\CommonMark\Environment::createCommonMarkEnvironment();
        $environment->addExtension(new Webuni\CommonMark\TableExtension\TableExtension());
        $environment->addExtension(new Webuni\CommonMark\AttributesExtension\AttributesExtension());

        $environment->addBlockParser(new HttpMethodParser());
        $environment->addInlineParser(new AutoLinkParser());

        $environment->addBlockRenderer('League\CommonMark\Block\Element\Heading',new SmartWiki\Extentions\Markdown\Renderer\HeadingRenderer());
        $environment->addBlockRenderer('League\CommonMark\Block\Element\Document',new SmartWiki\Extentions\Markdown\Renderer\TocRenderer());
        $environment->addBlockRenderer('SmartWiki\Extentions\Markdown\Element\HttpMethodBlock', new HttpMethodRenderer());

        $converter = new League\CommonMark\Converter(new League\CommonMark\DocParser($environment), new League\CommonMark\HtmlRenderer($environment));


        $html = $converter->convertToHtml($text);

        return $html;
    }
}

if(!function_exists('resolve_attachicons')) {
    /**
     * 获取对应扩展名的小图标
     * @param $ext
     * @return string|null
     */
    function resolve_attachicons($ext){
        $ext = strtolower($ext);
        $config =  config('attachicons');
        if(is_array($config) && isset($config[$ext])){
            return $config[$ext];
        }
        if(is_array($config) && isset($config['default'])){
            return $config['default'];
        }
        return null;
    }

}