<?php
if(file_exists(__DIR__ . '/install.lock')){
    header('location:/');
}

$basePath = substr(__DIR__,0,-6);

$lists[__DIR__]['read'] = is_readable(__DIR__);
$lists[__DIR__]['write'] = is_writable(__DIR__);
$lists[__DIR__]['executable'] = is_executable (__DIR__);

$storagePath = $basePath . 'storage/';

$lists[$storagePath]['read'] = is_readable($storagePath);
$lists[$storagePath]['write'] = is_writable($storagePath);
$lists[$storagePath]['executable'] = is_executable ($storagePath);

$vendorPath = $basePath .'vendor/';

$lists[$vendorPath]['read'] = is_readable($vendorPath);
$lists[$vendorPath]['write'] = is_writable($vendorPath);
$lists[$vendorPath]['executable'] = is_executable ($vendorPath);

$cachePath = $basePath . 'bootstrap/cache/';

$lists[$cachePath]['read'] = is_readable($cachePath);
$lists[$cachePath]['write'] = is_writable($cachePath);
$lists[$cachePath]['executable'] = is_executable ($cachePath);

$uploadPath = __DIR__ .'/uploads/';

$lists[$uploadPath]['read'] = is_readable($uploadPath);
$lists[$uploadPath]['write'] = is_writable($uploadPath);
$lists[$uploadPath]['executable'] = is_executable ($uploadPath);

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

?>
<!DOCTYPE html>
<html>
<head>
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
