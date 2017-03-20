<?php
if(file_exists(__DIR__ . '/install.lock')){
    header('location:/');
}

$basePath = substr(__DIR__,0,-6);
@rmdir(__DIR__ . '/temp');

$lists[__DIR__]['read'] = is_readable(__DIR__);
$lists[__DIR__]['write'] = is_writable(__DIR__);
$lists[__DIR__]['executable'] = @mkdir (__DIR__ . '/temp');
@rmdir(__DIR__ . '/temp');

$storagePath = $basePath . 'storage';
@rmdir ($storagePath . '/temp');

$lists[$storagePath]['read'] = is_readable($storagePath);
$lists[$storagePath]['write'] = is_writable($storagePath);
$lists[$storagePath]['executable'] = @mkdir($storagePath . '/temp');

@rmdir ($storagePath . '/temp');

$vendorPath = $basePath .'vendor';
@rmdir($vendorPath.'/temp');

$lists[$vendorPath]['read'] = is_readable($vendorPath);
$lists[$vendorPath]['write'] = is_writable($vendorPath);
$lists[$vendorPath]['executable'] = @mkdir($vendorPath.'/temp');

@rmdir($vendorPath.'/temp');

$cachePath = $basePath . 'bootstrap'. DIRECTORY_SEPARATOR. 'cache' ;
@rmdir($cachePath . '/temp');

$lists[$cachePath]['read'] = is_readable($cachePath);
$lists[$cachePath]['write'] = is_writable($cachePath);
$lists[$cachePath]['executable'] = @mkdir ($cachePath . '/temp');

@rmdir($cachePath . '/temp');

$uploadPath = __DIR__ . DIRECTORY_SEPARATOR .'uploads';
@rmdir($uploadPath . '/temp');

$lists[$uploadPath]['read'] = is_readable($uploadPath);
$lists[$uploadPath]['write'] = is_writable($uploadPath);
$lists[$uploadPath]['executable'] = @mkdir ($uploadPath . '/temp');
@rmdir($uploadPath . '/temp');

$extends['fileinfo'] = extension_loaded('fileinfo');
$extends['gd'] = extension_loaded('gd');
$extends['iconv'] = extension_loaded('iconv');
$extends['json'] = extension_loaded('json');
$extends['mbstring'] = extension_loaded('mbstring');
$extends['pdo'] = extension_loaded('pdo');
$extends['pdo_mysql'] = extension_loaded('pdo_mysql');
$extends['openssl'] = extension_loaded('openssl');
$extends['tokenizer'] = extension_loaded('tokenizer');
$extends['ctype'] = extension_loaded('ctype');
$extends['curl'] = extension_loaded('curl');

if(!file_exists($basePath . '.env') && file_exists($basePath.'.env.example')) {
    $env = file_get_contents($basePath.'.env.example');

    $env = str_replace('APP_DEBUG=true', 'APP_DEBUG=false', $env);

    if(function_exists('openssl_random_pseudo_bytes')) {
        $secure = true;
        $app_key = 'base64:' . base64_encode(openssl_random_pseudo_bytes(32,$secure));

        $env = str_replace('APP_KEY=', 'APP_KEY=' . $app_key, $env);
    }
    file_put_contents($basePath.'.env', $env);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>SmartWiki安装</title>
    <link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="static/bootstrap/js/html5shiv.min.js"></script>
    <script src="static/bootstrap/js/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="static/scripts/jquery.min.js"></script>
    <style>
        html, body {
            height: 100%;
            font-family: "Helvetica Neue", Helvetica, Microsoft Yahei, Hiragino Sans GB, WenQuanYi Micro Hei, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            font-family: 'Lato';
        }

        .container {
            width: 660px;
            padding: 15px;
            border-radius: 4px 4px 0 0;
            margin: 50px auto;
            border: 1px solid #ddd;
        }
        #error-message{
            padding-left: 20px;
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h3 style="margin-top: 0;text-align: center;margin-bottom: 20px;">SmartWiki安装</h3>
    <div class="alert alert-danger" role="alert" id="error-message">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <span id="error-message-content"></span>
    </div>
    <form method="post" action="{{route('install.index')}}" class="form-horizontal" role="form">
        <div>
            <h4>PHP版本 <span style="font-size: 12px;">PHP版本必须大于等于5.6</span></h4>
            <table class="table">
                <tbody><tr><td>当前PHP版本</td><td><?php echo PHP_VERSION;?> </td></tr></tbody>
            </table>
        </div>
        <hr>
        <div>
            <h4>目录权限检测</h4>
        <table class="table">
            <thead>
            <tr>
                <th>目录</th><th>读</th><th>写</th><th>执行</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($lists as $path=>$item):?>
            <tr>
                <td><?php echo $path;?></td>
                <td><?php echo $item['read']?'<span style="color:green;">[√]</span>' : '<span style="color:red;">[×]</span>' ;?></td>
                <td><?php echo $item['write']?'<span style="color:green;">[√]</span>' : '<span style="color:red;">[×]</span>'; ?></td>
                <td><?php echo $item['executable']?'<span style="color:green;">[√]</span>' : '<span style="color:red;">[×]</span>'; ?></td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        </div>
        <hr>
        <div>
            <h4>PHP扩展检测</h4>
            <table class="table">
                <thead><tr><th>扩展名</th><th>是否安装</th></tr></thead>
                <tbody>
                <?php foreach ($extends as $name=>$isLoad):?>
                <tr>
                    <td><?php echo $name;?></td>
                    <td><?php echo $isLoad?'<span style="color:green;">[√]</span>' : '<span style="color:red;">[×]</span>' ;?></td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <div class="form-group text-center">
            <a href="install" type="submit" class="btn btn-success" id="btn-install" data-loading-text="安装中...">
                下一步
            </a>

        </div>
    </form>
</div>
</body>
</html>
