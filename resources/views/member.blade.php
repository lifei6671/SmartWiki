<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="author" content="SmartWiki" />
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{wiki_config('SITE_NAME','SmartWiki')}}</title>
    <!-- Bootstrap -->
    <link href="{{asset('static/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/styles/styles.css')}}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="{{asset('static/bootstrap/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('static/bootstrap/js/respond.min.js')}}"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('static/scripts/jquery.min.js')}}"></script>
    @yield('styles')
</head>
<body>
<div class="manual-reader">
<header class="navbar navbar-static-top smart-nav navbar-fixed-top" role="banner">
    <div class="container">
        <div class="navbar-header col-sm-12 col-md-6 col-lg-5">
            <a href="{{route('home.index')}}" class="navbar-brand">{{wiki_config('SITE_NAME','SmartWiki')}}</a>
            <div class="btn-group dropdown-menu-right pull-right slidebar visible-xs-inline-block visible-sm-inline-block">
                <button class="btn btn-default dropdown-toggle hidden-lg" type="button" data-toggle="dropdown"><i class="fa fa-align-justify"></i></button>
                <ul class="dropdown-menu" role="menu">
                    <li{!! (isset($member_projects) ? ' class="active"' : '') !!}><a href="{{route('member.projects')}}" class="item"><i class="fa fa-sitemap"></i> 我的项目</a> </li>
                    <li{!! (isset($member_index) ? ' class="active"' : '') !!}><a href="{{route('member.index')}}" class="item"><i class="fa fa-user"></i> 个人资料</a> </li>
                    <li{!! (isset($member_account) ? ' class="active"' : '') !!}><a href="{{route('member.account')}}" class="item"><i class="fa fa-lock"></i> 修改密码</a> </li>
                    @if(isset($member->group_level) and $member->group_level === 0)
                        <li{!! (isset($member_setting) ? ' class="active"' : '') !!}><a href="{{route('member.setting')}}" class="item"><i class="fa fa-gear"></i> 开发配置</a> </li>
                        <li{!! (isset($setting_site) ? ' class="active"' : '') !!}><a href="{{route('setting.site')}}" class="item"><i class="fa fa-cogs"></i> 网站设置</a> </li>
                        <li{!! (isset($member_users) ? ' class="active"' : '') !!}><a href="{{route('member.users')}}" class="item"><i class="fa fa-group"></i> 用户管理</a> </li>
                    @endif
                    <li>
                        <a href="{{route('account.logout')}}" title="退出登录"><i class="fa fa-sign-out"></i> 退出登录</a>
                    </li>
                </ul>
            </div>
        </div>
        <nav class="navbar-collapse hidden-xs hidden-sm" role="navigation">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="{{route('account.logout')}}" title="退出登录"><i class="fa fa-sign-out"></i></a>
                </li>
                <li>
                    <a href="{{route('member.index')}}" class="img"><img src="{{$member->headimgurl}}" class="img-circle" style="width: 43px;"></a>
                </li>
            </ul>
        </nav>
    </div>
</header>
<div class="container member">
    <div class="row">
        <div class="page-left visible-lg-inline-block visible-md-inline-block">
            <ul class="menu">
                <li{!! (isset($member_projects) ? ' class="active"' : '') !!}><a href="{{route('member.projects')}}" class="item"><i class="fa fa-sitemap"></i> 我的项目</a> </li>
                <li{!! (isset($member_index) ? ' class="active"' : '') !!}><a href="{{route('member.index')}}" class="item"><i class="fa fa-user"></i> 个人资料</a> </li>
                <li{!! (isset($member_account) ? ' class="active"' : '') !!}><a href="{{route('member.account')}}" class="item"><i class="fa fa-lock"></i> 修改密码</a> </li>

                @if(isset($member->group_level) and $member->group_level === 0)
                    <li{!! (isset($member_setting) ? ' class="active"' : '') !!}><a href="{{route('member.setting')}}" class="item"><i class="fa fa-gear"></i> 开发配置</a> </li>
                    <li{!! (isset($setting_site) ? ' class="active"' : '') !!}><a href="{{route('setting.site')}}" class="item"><i class="fa fa-cogs"></i> 网站设置</a> </li>
                    <li{!! (isset($member_users) ? ' class="active"' : '') !!}><a href="{{route('member.users')}}" class="item"><i class="fa fa-group"></i> 用户管理</a> </li>
                @endif
                @if(isset($member->group_level) and $member->group_level < 2)
                    <li><a href="{{route('runapi.index')}}" class="item"><i class="fa fa-wrench"></i> 接口工具</a> </li>
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
</div>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('static/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('static/scripts/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script src="{{asset('static/scripts/scripts.js')}}" type="text/javascript"></script>
@yield('scripts')
</body>
</html>