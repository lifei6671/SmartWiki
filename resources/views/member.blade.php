<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{wiki_config('SITE_NAME','SmartWiki')}}</title>

    <!-- Bootstrap -->
    <link href="/static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/static/styles/styles.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/static/bootstrap/js/html5shiv.min.js"></script>
    <script src="/static/bootstrap/js/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/scripts/jquery.min.js"></script>
    @yield('styles')
</head>
<body>
<header class="navbar navbar-static-top smart-nav navbar-fixed-top" role="banner">
    <div class="container">
        <div class="navbar-header">
            <a href="{{route('home.index')}}" class="navbar-brand">SmartWiki</a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="{{route('account.logout')}}" title="退出登录">
                        <i class="fa fa-sign-out"></i>
                    </a>
                </li>
                <li>
                    <a href="{{route('member.index')}}" class="img">
                        <img src="{{$member->headimgurl}}" class="img-circle" style="width: 43px;">
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>
<div class="container smart-container member">
    <div class="row">
        <div class="page-left">
            <ul class="menu">
                <li{!! (isset($member_index) ? ' class="active"' : '') !!}><a href="{{route('member.index')}}" class="item"><i class="fa fa-user"></i> 个人资料</a> </li>
                <li{!! (isset($member_account) ? ' class="active"' : '') !!}><a href="{{route('member.account')}}" class="item"><i class="fa fa-lock"></i> 修改密码</a> </li>
                <li{!! (isset($member_projects) ? ' class="active"' : '') !!}><a href="{{route('member.projects')}}" class="item"><i class="fa fa-sitemap"></i> 项目列表</a> </li>
                @if(isset($member->group_level) and $member->group_level === 0)
                    <li{!! (isset($member_setting) ? ' class="active"' : '') !!}><a href="{{route('member.setting')}}" class="item"><i class="fa fa-gear"></i> 网站设置</a> </li>
                    <li{!! (isset($member_users) ? ' class="active"' : '') !!}><a href="{{route('member.users')}}" class="item"><i class="fa fa-group"></i> 用户管理</a> </li>
                @endif
            </ul>
        </div>
        <div class="page-right">
            @yield('content')
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<footer class="member-footer">
    <div class="container">
        <div class="row text-center">
            <ul>
                <li><a href="https://www.iminho.me">SmartWiki</a></li>
                <li>&nbsp;·&nbsp;</li>
                <li><a href="https://github.com/lifei6671/SmartWiki/issues" target="_blank">意见反馈</a> </li>
                <li>&nbsp;·&nbsp;</li>
                <li><a href="https://github.com/lifei6671/SmartWiki">Github</a> </li>
            </ul>

        </div>
    </div>
</footer>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/static/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/static/scripts/jquery.form.js"></script>
<script type="text/javascript" src="/static/layer/layer.js"></script>
<script src="/static/scripts/scripts.js" type="text/javascript"></script>
@yield('scripts')
</body>
</html>