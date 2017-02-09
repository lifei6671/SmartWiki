<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SmartWiki" />
    <title>提示信息 - {{wiki_config('SITE_NAME','SmartWiki')}}</title>

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
        <div class="login-body" style="width: 600px;padding-top: 20px;">
            <form role="form" method="post">
                <h3 class="text-center">{{$title}}</h3>
                <div class="form-group text-center" style="margin-top: 50px;">
                    <p>{!! $message !!}</p>
                </div>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
@include('widget.footer')
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('static/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
</body>
</html>