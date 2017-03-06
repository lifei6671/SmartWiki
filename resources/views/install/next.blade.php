<!DOCTYPE html>
<html>
<head>
    <title>SmartWiki</title>
    <link href="{{asset('static/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="{{asset('static/bootstrap/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('static/bootstrap/js/respond.min.js')}}"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('static/scripts/jquery.min.js')}}"></script>
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
        <div class="form-group">
            <label class="col-sm-3" for="data-account">数据库地址：</label>
            <div class="col-sm-9">
                <div class="col-sm-9 pull-left">
                    <input type="text" class="form-control" value="127.0.0.1" name="dataAddress" id="data-address" placeholder="数据库地址">
                </div>
                <div class="col-sm-3 pull-right">
                    <input name="dataPort" type="text" class="form-control col-sm-2" value="3306" placeholder="端口号">
                </div>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="data-name">数据库名称：</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="smart_wiki" name="dataName" id="data-name" placeholder="数据库名称">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="data-account">数据库账号：</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="root" name="dataAccount" id="data-account" placeholder="数据库账号">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="data-password">数据库密码：</label>
            <div class="col-sm-9">
                <input type="password" class="form-control" name="dataPassword" id="data-password" placeholder="数据库密码">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="account">管理员账号：</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="admin" name="account" id="account" placeholder="管理员账号">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="password">管理员密码：</label>
            <div class="col-sm-9">
                <input type="password" class="form-control" name="password" id="password" placeholder="管理员密码">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3" for="password">管理员邮箱：</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" name="email" id="email" placeholder="管理员邮箱">
            </div>
        </div>
        <hr>
        <div class="form-group text-center">
            <a href="install.php" class="btn btn-success">
                上一步
            </a>
            <button type="submit" class="btn btn-success" id="btn-install" data-loading-text="安装中...">
                立即安装
            </button>
        </div>
    </form>
</div>
<script src="{{asset('static/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('static/scripts/jquery.form.js')}}"></script>
<script type="text/javascript">
    $(function () {
        function showMessage($msg) {
            $("#error-message").show().find('#error-message-content').text($msg);
            return false;
        }
        $("form").ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                if(!$("#data-address").val()){
                    return showMessage('数据库地址不能为空');
                }
                if(!$("#data-account").val()){
                    return showMessage('数据库账号不能为空');
                }
                if(!$("#data-name").val()){
                    return showMessage('数据库名称不能为空');
                }
                if(!$("#data-password").val()){
                    return showMessage('数据库密码不能为空');
                }

                if(!$("#account").val()){
                    return showMessage('管理员账号不能为空');
                }
                if(!$("#password").val()){
                    return showMessage('管理员密码不能为空');
                }
                if(!$("#email").val()){
                    return showMessage('管理员邮箱不能为空');
                }
                $("#btn-install").button('loading');
            },
            success: function (res) {
                $("#btn-install").button('reset');
                if(res.errcode != 0){
                    showMessage(res.message);
                }else{
                    window.location = res.data.url;
                }
            },
            error: function () {
                $("#btn-install").button('reset');
                showMessage('服务器错误');
            }
        });
    });
</script>
</body>
</html>
