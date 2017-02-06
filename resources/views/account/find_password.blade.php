<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>找回密码 - {{wiki_config('SITE_NAME','SmartWiki')}} - Powered by SmartWiki</title>

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
        <div class="login-body">
            <form role="form" method="post">
                <h3 class="text-center">找回密码</h3>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-at"></i>
                        </div>
                        <input type="email" class="form-control" placeholder="邮箱" name="email" id="email" autocomplete="off">
                    </div>
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
                    <button type="button" id="btn-login" class="btn btn-success" style="width: 100%"  data-loading-text="正在发送...">找回密码</button>
                </div>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
@include('widget.footer')
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="text/javascript" src="{{asset('static/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('static/scripts/scripts.js')}}"></script>
<script type="text/javascript">
    $(function () {
        $("#email,#code").on('focus',function () {
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

            var email = $.trim($("#email").val());
            var code = $.trim($("#code").val());

            if(email == ""){
                $("#email").tooltip({placement:"auto",title : "邮箱不能为空",trigger : 'manual'})
                    .tooltip('show')
                    .parents('.form-group').addClass('has-error');
                $btn.button('reset');
                return false;

            }else if(code == ""){
                $("#code").tooltip({title : '验证码不能为空',trigger : 'manual'})
                    .tooltip('show')
                    .parents('.form-group').addClass('has-error');
                $btn.button('reset');
                return false;
            }else{
                $.ajax({
                    url : "{{route('account.find_password')}}",
                    data : $("form").serializeArray(),
                    dataType : "json",
                    type : "POST",
                    success : function (res) {

                        if(res.errcode != 0){
                            $("#captcha-img").click();
                            $("#code").val('');
                            layer.msg(res.message);
                            $btn.button('reset');
                        }else{
                            window.location = res.data.url;
                        }
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