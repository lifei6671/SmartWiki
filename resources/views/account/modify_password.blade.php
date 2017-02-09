<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SmartWiki" />
    <title>修改密码 - {{wiki_config('SITE_NAME','SmartWiki')}}</title>

    <!-- Bootstrap -->
    <link href="{{asset('static/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/styles/styles.css')}}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{asset('static/bootstrap/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('static/bootstrap/js/respond.min.js')}}"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('static/scripts/jquery.min.js')}}"></script>
</head>
<body>
<header class="navbar navbar-static-top smart-nav navbar-fixed-top" role="banner">
    <div class="container">
        <div class="navbar-header">
            <a href="{{route('home.index')}}" class="navbar-brand">SmartWiki</a>
        </div>
    </div>
</header>
<div class="container smart-container">
    <div class="row login">
        @if(isset($message) && isset($title))
            <div class="login-body" style="width: 600px;padding-top: 20px;">
                <form role="form" method="post">
                    <h3 class="text-center">{{$title}}</h3>
                    <div class="form-group text-center" style="margin-top: 50px;">
                        <p>{!! $message !!}</p>
                    </div>
                </form>
            </div>
        @else
        <div class="login-body">
            <form role="form" method="post">
                <input type="hidden" name="token" value="{{$token}}">
                <h3 class="text-center">修改密码</h3>

                <div class="form-group">
                    <label for="newPasswd">新密码</label>
                    <input type="password" class="form-control" name="passowrd" id="newPassword" maxlength="20" placeholder="新密码"  autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="configPasswd">确认密码</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" maxlength="20" placeholder="确认密码"  autocomplete="off">
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-check-square"></i>
                        </div>
                        <input type="text" name="code" id="code" class="form-control" style="width: 150px" maxlength="5" placeholder="验证码" autocomplete="off">&nbsp;
                        <img id="captcha-img" src="{{route('captcha.verify')}}" onclick="this.src='/verify?key=login&t='+(new Date()).getTime();" title="点击换一张">
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="btn-login" class="btn btn-success" style="width: 100%"  data-loading-text="正在修改...">立即修改</button>
                </div>
            </form>
        </div>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
@include('widget.footer')
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('static/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script src="{{asset('static/scripts/scripts.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $("#newPassword,#confirmPassword,#code").on('focus',function () {
            $(this).tooltip('destroy').parents('.form-group').removeClass('has-error');;
        });

        $(document).keydown(function (e) {
            var event = document.all ? window.event : e;
            if(event.keyCode == 13){
                $("#btn-login").click();
            }
        });
        $("#btn-login").on('click',function () {
            var $btn = $(this).button('loading');

            var newPassword = $.trim($("#newPassword").val());
            var confirmPassword = $.trim($("#confirmPassword").val());
            var code = $.trim($("#code").val());

            if(newPassword == ""){
                $("#newPassword").tooltip({placement:"auto",title : "密码不能为空",trigger : 'manual'})
                    .tooltip('show')
                    .parents('.form-group').addClass('has-error');
                $btn.button('reset');
                return false;

            }else if(confirmPassword == ""){
                $("#confirmPassword").tooltip({placement:"auto",title : "确认密码不能为空",trigger : 'manual'})
                    .tooltip('show')
                    .parents('.form-group').addClass('has-error');
                $btn.button('reset');
                return false;
            }else if(newPassword != confirmPassword) {

            }else if(code == ""){
                $("#code").tooltip({title : '验证码不能为空',trigger : 'manual'})
                    .tooltip('show')
                    .parents('.form-group').addClass('has-error');
                $btn.button('reset');
                return false;
            }else{
                $.ajax({
                    url : "{{route('account.modify_password',['key' => $token])}}",
                    data : $("form").serializeArray(),
                    dataType : "json",
                    type : "POST",
                    success : function (res) {

                        if(res.errcode != 0){
                            $("#captcha-img").click();
                            $("#code").val('');
                            layer.msg(res.message);
                        }else{
                            window.location = res.data.url;
                        }
                        $btn.button('reset');
                    },
                    error :function () {
                        $("#captcha-img").click();
                        $("#code").val('');
                        layer.msg('系统错误');
                        $btn.button('reset');
                    }
                });
            }


            return false;
        });
    });
</script>
</body>
</html>