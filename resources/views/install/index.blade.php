<!DOCTYPE html>
<html>
<head>
    <title>SmartWiki安装</title>
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
        <table class="table">
            <thead>
            <tr>
                <th>目录</th><th>读</th><th>写</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lists as $path=>$item)
            <tr>
                <td>{{$path}}</td><td>{!! $item['read']?'<span style="color:green;">[√]</span>' : '<span style="color:red;">[×]</span>'!!}</td><td>{!! $item['write']?'<span style="color:green;">[√]</span>' : '<span style="color:red;">[×]</span>'!!}</td>
            </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-success" id="btn-install" data-loading-text="安装中...">
                下一步
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
