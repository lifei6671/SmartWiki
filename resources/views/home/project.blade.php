<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{$project->title}} - {{wiki_config('SITE_NAME','SmartWiki')}}</title>

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
<div class="manual-reader">
    <header class="navbar navbar-static-top smart-nav navbar-fixed-top" role="banner">
        <div class="container">
            <div class="navbar-header">
                <a href="{{route('home.index')}}" class="navbar-brand">SmartWiki</a>
                <div class="searchbar pull-left">
                    <form class="form-inline" action="{{route('search.search')}}" method="get">
                        <input class="form-control" name="keyword" type="search" placeholder="请输入关键词...">
                        <button class="search-btn">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="btn-group dropdown-menu-right pull-right slidebar">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-align-justify"></i></button>
                    <ul class="dropdown-menu" role="menu">
                        @if(isset($member))
                            <li>
                                <a href="{{route('account.logout')}}" title="退出登录">
                                    <i class="fa fa-sign-out"></i> 退出登录
                                </a>
                            </li>
                            <li>
                                <a href="{{route('member.index')}}" class="img" title="个人中心">
                                    <i class="fa fa-user"></i> 个人中心
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{route('account.login')}}" title="用户登录">登录</a>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>

            <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
                <ul class="nav navbar-nav navbar-right">
                    @if(isset($member) && ($member->group_level == 0 || $member->group_level == 1))
                        <li>
                            <a href="javascript:;" data-toggle="modal" data-target="#create-project" title="创建项目"><i class="fa fa-plus"></i></a>
                        </li>
                    @endif
                    @if(isset($member))
                        <li>
                            <a href="{{route('account.logout')}}" title="退出登录">
                                <i class="fa fa-sign-out"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('member.index')}}" class="img" title="个人中心">
                                <img src="{{$member['headimgurl']}}" class="img-circle" style="width: 43px;">
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{route('account.login')}}" title="用户登录">登录</a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </header>
    <div class="container smart-container manual-project">
        <div class="manual-project-box">
            <div class="pull-left">
                <div class="manual-project-box-img">
                    <img src="{{asset('static/images/project_default.png')}}" width="50">
                </div>
                <div class="manual-project-box-title">
                    {{$project->title}}
                </div>
                <div class="author">
                    {{$author}}
                </div>
            </div>
            <div class="pull-right">
                <div class="manual-project-box-qrcode">
                    <img src="{{route('qrcode.index',['id'=>$project->project_id])}}">
                    <div class="text-center">扫一扫用手机阅读</div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="manual-project-body">
            <div class="tab-head">
                <a href="javascript:;" class="tab-item active">概要</a>
                <a href="javascript:;" class="tab-item">目录</a>
                <a href="javascript:;" class="tab-item">记录</a>
                <div class="clearfix"></div>
            </div>
            <div class="tab-body">

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@include('widget.footer')
</div>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('static/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('static/scripts/scripts.js')}}" type="text/javascript"></script>


<script type="text/javascript">


</script>
</body>
</html>