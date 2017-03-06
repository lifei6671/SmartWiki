<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SmartWiki" />
    <title>用户注册 - {{wiki_config('SITE_NAME','SmartWiki')}}</title>

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
            <a href="{{route('home.index')}}" class="navbar-brand"> {{wiki_config('SITE_NAME','SmartWiki')}}</a>
        </div>
    </div>
</header>
<div class="container smart-container">
    <div class="row login">
        <div class="login-body">
            <form role="form" method="post" id="registerForm" action="{{route("account.register")}}" class="">
                <h3 class="text-center">用户注册</h3>
                <div class="form-group">
                    <label class="control-label">用户名</label>

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                            <input type="text" class="form-control" placeholder="用户名" name="account" id="account" autocomplete="off">
                        </div>
                </div>
                <div class="form-group">
                    <label class="control-label">密码</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-lock"></i>
                        </div>
                        <input type="password" class="form-control" placeholder="密码" name="password" id="password"  autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">确认密码</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-lock"></i>
                        </div>
                        <input type="password" class="form-control" placeholder="确认密码" name="confirm_password" id="confirm_password"  autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">邮箱</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-envelope-open"></i>
                        </div>
                        <input type="email" class="form-control" placeholder="邮箱" name="email" id="email"  autocomplete="off">
                    </div>
                </div>
                @if(wiki_config('ENABLED_CAPTCHA'))
                <div class="form-group">
                    <label class="control-label">验证码</label>

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-check-square"></i>
                            </div>
                            <input type="text" name="code" id="code" class="form-control" style="width: 150px" maxlength="5" placeholder="验证码" autocomplete="off">&nbsp;
                            <img id="captcha-img" src="{{route('captcha.verify')}}" onclick="this.src='/verify?key=login&t='+(new Date()).getTime();" title="点击换一张">
                        </div>
                </div>
                @endif

                <div class="form-group">
                    <button type="submit" id="btnRegister" class="btn btn-success" style="width: 100%"  data-loading-text="正在登录..." autocomplete="off">立即登录</button>
                </div>
                <div class="checkbox">
                    已有账号？<a href="{{route('account.login')}}" style="display: inline-block;">立即登录</a>
                </div>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
@include('widget.footer')
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('static/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script src="{{asset('static/scripts/jquery.form.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $("#account,#password,#confirm_password,#code").on('focus',function () {
            $(this).tooltip('destroy').parents('.form-group').removeClass('has-error');;
        });

        $(document).keyup(function (e) {
            var event = document.all ? window.event : e;
            if(event.keyCode === 13){
                $("#btnRegister").trigger("click");
            }
        });
        $("#registerForm").ajaxForm({
            beforeSubmit : function () {
                var account = $.trim($("#account").val());
                var password = $.trim($("#password").val());
                var confirmPassword = $.trim($("#confirm_password").val());
                var code = $.trim($("#code").val());
                var email = $.trim($("#email").val());

                if(account === ""){
                    $("#account").focus().tooltip({placement:"auto",title : "账号不能为空",trigger : 'manual'})
                        .tooltip('show')
                        .parents('.form-group').addClass('has-error');
                    return false;

                }else if(password === ""){
                    $("#password").focus().tooltip({title : '密码不能为空',trigger : 'manual'})
                        .tooltip('show')
                        .parents('.form-group').addClass('has-error');
                    return false;
                }else if(confirmPassword !== password){
                    $("#confirm_password").focus().tooltip({title : '确认密码不正确',trigger : 'manual'})
                        .tooltip('show')
                        .parents('.form-group').addClass('has-error');
                    return false;
                }else if(email === ""){
                    $("#email").focus().tooltip({title : '邮箱不能为空',trigger : 'manual'})
                        .tooltip('show')
                        .parents('.form-group').addClass('has-error');
                    return false;
                    @if(wiki_config('ENABLED_CAPTCHA'))
                }else if(code !== undefined && code === ""){
                    $("#code").focus().tooltip({title : '验证码不能为空',trigger : 'manual'})
                        .tooltip('show')
                        .parents('.form-group').addClass('has-error');
                    return false;
                    @endif
                }else {

                    $("button[type='submit']").button('loading');
                }
            },
            success : function (res) {
                $("button[type='submit']").button('reset');
                if(res.errcode == 0){
                    window.location = "/";
                }else{
                    $("#captcha-img").click();
                    $("#code").val('');
                    layer.msg(res.message);
                }
            }
        });
    });
</script>
</body>
</html>