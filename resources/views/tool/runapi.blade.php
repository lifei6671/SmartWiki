<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="author" content="SmartWiki" />
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>接口测试工具 - {{wiki_config('SITE_NAME','SmartWiki')}}</title>

    <!-- Bootstrap -->
    <link href="{{asset('static/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">

    <link href="{{asset('static/styles/styles.css')}}" rel="stylesheet">
    <link href="{{asset('static/bootstrap/icheck/skins/square/square.css')}}" rel="stylesheet">
    <link href="{{asset('static/codemirror/lib/codemirror.css')}}" rel="stylesheet">
    <style type="text/css">

        .CodeMirror { height: 250px; border: 1px solid #ddd; font-family: "Courier New", 'Source Sans Pro', Helvetica, Arial, sans-serif;font-size: 12px;}
        .CodeMirror-scroll { max-height: 250px; }
        .CodeMirror pre { padding-left: 7px; line-height: 1.25; }
        .CodeMirror .CodeMirror-linenumber{font-size: 12px;min-width: 21px;}

        .tool-container{
            min-width: 500px;
        }
        .tool-api-response{
            background: #FAFAFA;
            border-top: 1px solid #DDDDDD;
        }
        .response-info{
            line-height: 40px;
            font-size: 12px;
            margin-right: 15px;
            color: #989898;
        }
        .response-info .result{
            color: #0978EE;
        }
        .tool-api-response .nav-tabs li a{
            border-bottom: 2px solid transparent;
        }
        .tool-api-response .nav-tabs li.active a{
            border: 0;
            background: inherit;
            border-bottom: 2px solid #F47023;
        }
        .page-left{
            position: fixed;
            overflow: auto;
            top: 50px;
            bottom: 0;
            z-index: 100;
            width: 300px;
            background-color: #f5f5f5;
            border-right: 1px solid #eaeaea
        }
        .page-right{
            padding: 15px 0 15px 24px;
            margin-left: 315px;
            margin-right: 20px;
            min-width: 660px;
        }
        .page-right>.row{
            margin: 0;
        }
        .tool-api-menu-top{
            text-align: center;
            border-bottom: 1px solid #DBDBDB;
            background: #F3F3F3;
        }
        .tool-api-menu-top a{
            text-decoration: none;
            color: #9A3D7C;
        }
        .tool-api-menu-top a:hover{
            background: #DDDDBB;
        }
        .tool-api-action{
            display: inline-block;
            padding: 10px 0px;
            width: 50%;
        }
        .tool-api-menu,.tool-api-menu-submenu{
            margin: 0px auto;
            color: #505050;
            list-style: none;
            padding: 0;
            line-height: 20px;
        }
        .tool-api-menu a{
            color: #505050;
            display: block;
            padding: 10px 0;
            text-decoration: none;
            border-bottom: 1px solid #DBDBDB;

        }
        .tool-api-menu a:hover{
            text-decoration: none;
            background: #F0F0F0;
        }
        .tool-api-menu .tool-api-menu-title{
            display: inline-block;
        }
        .tool-api-menu .tool-api-menu-title .text{
            font-size: 10px;
            color: #919191;
        }
        .tool-api-menu>li{
            display: block;
            position: relative;
        }
        .tool-api-menu>li .fa{
            display: inline-block;
            width: 40px;
            text-align: center;
            font-size: 24px;
            vertical-align: super;
        }
        .tool-api-menu .btn>.fa{
            vertical-align: baseline;
            vertical-align: -webkit-baseline-middle;
        }
        .tool-api-menu .btn-group-more{
            position: absolute;top:0;right: 0;
        }

        .tool-api-menu .btn-more{
            position: absolute;top:0;right: 0;
            border-radius:0;
            background:transparent;
            padding-left: 1px;
            padding-right: 1px;
            box-shadow: none !important;
        }

        .tool-api-menu>li>.tool-api-menu-submenu{
            position: relative;
            display: none;
        }
        .tool-api-menu .tool-api-menu-submenu .fa{
            vertical-align: bottom;
        }
        .tool-api-menu .tool-api-menu-submenu .btn-more{
            height: 44px;
        }

        .tool-api-menu .menu-title{
            display: inline-block;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            width: 140px;
            line-height: 20px;
        }
        .tool-api-menu .api-items{
            margin: 0;
            padding: 0;
            position: relative;
            font-size: 12px;
            display: none;
        }
        .tool-api-menu>li>.tool-api-menu-submenu,
        .tool-api-menu>li>.tool-api-menu-submenu>li>.api-items{
            display: none;
        }
        .tool-api-menu>.open-menu>.tool-api-menu-submenu,
        .tool-api-menu>.open-menu>.tool-api-menu-submenu>.open-menu>.btn-more,
        .tool-api-menu>.open-menu>.tool-api-menu-submenu>.open-menu>.api-items,
        .tool-api-menu>.open-menu>.api-items{
            display: block;
        }
        .tool-api-menu .api-items>li>a{
            padding: 12px 0 6px 0;
        }
        .tool-api-menu>li>.api-items>li .btn-more,
        .tool-api-menu .tool-api-menu-submenu .btn-group>.btn-more{
            display: none;
            box-shadow: none;
        }
        .tool-api-menu .api-items>li:hover>.btn-group>.btn-more,
        .tool-api-menu .tool-api-menu-submenu>li:hover>.btn-group-more>.btn-more,
        .tool-api-menu .tool-api-menu-submenu>.open-menu>.btn-group-more>.btn-more,
        .tool-api-menu .api-items>li>.open>.btn-more,
        .tool-api-menu .tool-api-menu-submenu>li>.open>.btn-more{
            display: block;
            box-shadow: none;
        }

        .dropdown-menu-more{
            right: 2px;
            top: 44px;
            left:inherit;
            padding: 0;
        }
        .dropdown-menu-more>li>a{
            padding: 10px 0 10px 15px;
            border: 0;
            line-height: 15px;
            font-size: 13px;
        }
        .dropdown-menu-more>li>a>.fa{
            display: inline-block;
            width: auto;
            font-size: 13px;
            vertical-align: baseline;
        }
        .tool-api-menu .method-default{
            display: inline-block;
            width: 50px;
            font-weight: bold;
            text-align: right;
            line-height: 20px;
            overflow: hidden;
        }
        .tool-api-menu .method-get{
            color: #7ED321;
        }
    </style>
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
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="{{route('home.index')}}" class="navbar-brand"> {{wiki_config('SITE_NAME','SmartWiki')}}</a>
                <div class="searchbar pull-left visible-lg-inline-block visible-md-inline-block">
                    <form class="form-inline" action="{{route('search.search')}}" method="get">
                        <input class="form-control" name="keyword" type="search" placeholder="请输入关键词...">
                        <button class="search-btn">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
                @include('widget.usermenu')
            </div>

            <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
                <ul class="nav navbar-nav navbar-right">
                    @if(isset($member) && ($member->group_level == 0 || $member->group_level == 1))
                        <li>
                            <a href="{{route('project.edit')}}" title="创建项目"><i class="fa fa-plus"></i></a>
                        </li>
                    @endif
                    @if(isset($member))
                        <li>
                            <a href="{{route('account.logout')}}" title="退出登录">
                                <i class="fa fa-sign-out"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('member.projects')}}" class="img" title="个人中心">
                                <img src="{{$member['headimgurl']}}" class="img-circle" style="width: 43px;">
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{route('account.login')}}" title="用户登录">登录</a>
                        </li>
                        @if(wiki_config("ENABLED_REGISTER"))
                            <li>
                                <a href="{{route('account.register')}}" title="用户登录">注册</a>
                            </li>
                        @endif
                    @endif
                </ul>
            </nav>
        </div>
    </header>
    <div class="container-fluid tool-container">
        <div class="page-left">
            <div class="tool-api-menu-top">
                <a href="javascript:;" class="pull-left tool-api-action">
                    <i class="fa fa-folder"></i> 添加分类
                </a>
                <a href="javascript:;" class="pull-right tool-api-action" style="border-left: 1px solid #DBDBDB;">
                    <i class="fa fa-plus"></i> 添加接口
                </a>
                <div class="clearfix"></div>
            </div>
            <ul class="tool-api-menu">
                <li>
                    <a href="javascript:;">
                        <i class="fa fa-folder"></i>
                        <div class="tool-api-menu-title">默认分类<br/><span class="text">0 个接口</span></div>
                    </a>
                    <div class="btn-group btn-group-more">
                        <button class="btn btn-more dropdown-toggle" style="height: 63px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-more">
                            <li><a href="javascript:;"><i class="fa fa-pencil"></i> 编辑</a></li>
                            <li><a href="javascript:;"><i class="fa fa-folder"></i> 添加分类</a> </li>
                            <li><a href="javascript:;"><i class="fa fa-trash"></i> 删除</a></li>
                        </ul>
                    </div>
                    <ul class="tool-api-menu-submenu">
                        <li>
                            <a href="javascript:;">
                                <i class="fa fa-folder-o"></i>
                                微信
                            </a>
                            <div class="btn-group btn-group-more">
                                <button class="btn btn-more dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-more">
                                    <li><a href="#"><i class="fa fa-pencil"></i> 编辑</a></li>
                                    <li><a href="#"><i class="fa fa-trash"></i> 删除</a></li>
                                </ul>
                            </div>
                            <ul class="api-items">
                                <li>
                                    <a href="javascript:;">
                                        <i class="fa"></i>
                                        <span class="method-default method-get">GET</span>
                                        <span class="menu-title">搜索订单搜索订单搜索订单搜索订单搜索订单搜索订单</span>
                                    </a>
                                    <div class="btn-group btn-group-more">
                                        <button class="btn btn-more dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-more">
                                            <li><a href="#"><i class="fa fa-pencil"></i> 编辑</a></li>
                                            <li><a href="#"><i class="fa fa-trash"></i> 删除</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="api-items">
                        <li>
                            <a href="javascript:;">
                                <span class="method-default method-get">GET</span>
                                <span class="menu-title">搜索订单搜索订单搜索订单搜索订单搜索订单搜索订单</span>
                            </a>
                            <div class="btn-group btn-group-more">
                                <button class="btn btn-more dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-more">
                                    <li><a href="#"><i class="fa fa-pencil"></i> 编辑</a></li>
                                    <li><a href="#"><i class="fa fa-trash"></i> 删除</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="page-right">
            <div class="row">


                <div class="tool-api-method">
                    <div class="row">
                        <div class="col-lg-9 col-sm-8 col-xs-7">
                            <div class="input-group">
                                <div class="input-group-btn" id="btn-http-group">
                                    <button type="button" id="httpMethod" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100px;">GET <span class="caret"></span></button>
                                    <ul class="dropdown-menu" style="width: 100px;min-width: 100px;">
                                        <li><a href="#">GET</a></li>
                                        <li><a href="#">POST</a></li>
                                        <li><a href="#">PUT</a></li>
                                        <li><a href="#">PATCH</a></li>
                                        <li><a href="#">DELETE</a></li>
                                        <li><a href="#">COPY</a></li>
                                        <li><a href="#">HEAD</a></li>
                                        <li><a href="#">OPTIONS</a></li>
                                        <li><a href="#">LINK</a></li>
                                        <li><a href="#">UNLINK</a></li>
                                        <li><a href="#">PURGE</a></li>
                                        <li><a href="#">LOCK</a></li>
                                        <li><a href="#">UNLOCK</a></li>
                                        <li><a href="#">PROPFND</a></li>
                                        <li><a href="#">VIEW</a></li>
                                    </ul>
                                </div><!-- /btn-group -->
                                <input type="text" class="form-control" id="requestUrl" aria-label="..." placeholder="请输入一个的URL" value="http://wiki.minho.com/tool/runapi">
                            </div><!-- /input-group -->
                        </div>
                        <div class="col-lg-3 col-sm-4 col-xs-5">
                            <button type="button" id="sendRequest" class="btn btn-primary" style="width: 70px"> 发 送</button>

                            <div class="btn-group">
                                <button class="btn btn-default" style="width: 70px">
                                    保 存
                                </button>
                                <button class="btn btn-default dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">保存到文档</a></li>
                                    <li><a href="#">生成 Markdown</a> </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row tool-api-parameter">
                    <ul class="nav nav-tabs" id="parameter-tab">
                        <li role="presentation" class="active"  href="#headers"><a href="#headers">Headers</a></li>
                        <li role="presentation"  href="#body"><a href="#body">Body</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="headers">
                            <table style="margin-top: 10px;width: 100%" class="parameter-active">
                                <tbody>
                                <tr>
                                    <td style="width: 100px;padding-right: 20px;"><label class="hide"><input type="checkbox" checked></label></td>
                                    <td style="width: 50%;"><input type="text" class="input-text" name="key" placeholder="key"></td>
                                    <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" name="value" placeholder="value"></td>
                                    <td style="width: 100px;padding-left: 20px;">
                                        <a href="javascript:;" class="parameter-close hide">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="body">
                            <ul class="nav nav-tabs parameter-post-list">
                                <li href="#x-www-form-urlencodeed"><label><input type="radio" name="parameterType" checked value="x-www-form-urlencodeed">x-www-form-urlencodeed</label></li>
                                <li href="#raw"><label><input type="radio" name="parameterType" value="raw">raw</label></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="x-www-form-urlencodeed">
                                    <table style="margin-top: 10px;width: 100%" class="parameter-active">
                                        <tbody>
                                        <tr>
                                            <td style="width: 100px;padding-right: 20px;"><label class="hide"><input type="checkbox" checked></label></td>
                                            <td style="width: 50%;"><input type="text" class="input-text" name="key" placeholder="key"></td>
                                            <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" name="value" placeholder="value"></td>
                                            <td style="width: 100px;padding-left: 20px;">
                                                <a href="javascript:;" class="parameter-close hide">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="raw">
                                    <textarea id="demotext"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row tool-api-response">
                    <ul class="nav nav-tabs">
                        <li href="#responseBody" class="active"><a href="javascript:;">Body</a> </li>
                        <li href="#responseCookie"><a href="javascript:;">Cookies</a> </li>
                        <li href="#responseHeader"><a href="javascript:;">Header</a> </li>
                        <div class="pull-right response-info">
                            <span>Status: <span class="result" id="httpCode">0</span></span>&nbsp;&nbsp;
                            <span>Time: <span class="result" id="httpTime">0 ms</span></span>
                        </div>
                    </ul>
                    <div class="tab-content" style="padding-top: 10px;">
                        <div role="tabpanel" class="tab-pane active" id="responseBody">
                            <textarea id="responseBodyContainer" style="display: none;"></textarea>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="responseCookie">
                            <table class="table table-condensed">
                                <thead>
                                <tr><th>Name</th><th>Value</th><th>Domian</th><th>Path</th><th>Expires</th><th>HTTP</th><th>Secure</th></tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="responseHeader">

                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>
    <script type="text/plain" id="parameter-template">
        <tr>
            <td style="width: 100px;padding-right: 20px;"><label class="hide"><input type="checkbox" checked></label></td>
            <td style="width: 50%;"><input type="text" class="input-text" placeholder="key" name="key"></td>
            <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" name="value" placeholder="value"></td>
            <td style="width: 100px;padding-left: 20px;">
                <a href="javascript:;" class="parameter-close hide">
                    <i class="fa fa-close"></i>
                </a>
            </td>
        </tr>
    </script>
</div>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('static/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('static/scripts/scripts.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('static/bootstrap/icheck/icheck.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script type="text/javascript" src="/static/codemirror/lib/codemirror.js"></script>
<script src="/static/codemirror/mode/xml/xml.js"></script>
<script src="/static/codemirror/mode/javascript/javascript.js"></script>
<script src="/static/codemirror/mode/css/css.js"></script>
<script src="/static/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="/static/codemirror/addon/edit/matchbrackets.js"></script>
<script type="text/javascript" src="{{asset('static/scripts/runapi.js')}}"></script>
<script type="text/javascript">
    window.RawEditor = null;

    function renderParameter() {
        $(".parameter-active>tbody>tr .input-text").off("focus");

        $(".parameter-active>tbody>tr:last-child .input-text").on("focus",function(){
            var html = $("#parameter-template").html();
            var $then = $(this).closest('tbody');

            $then.find("tr .hide").removeClass("hide");
            $then.append(html);
            $('input:checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                increaseArea: '10%'
            });
            renderParameter();
        });
        $(".parameter-close").on("click",function () {
            $(this).closest("tr").empty().remove();
        });
    }
    $(function () {
        renderParameter();
        $("#btn-http-group .dropdown-menu>li").on("click",function () {
            var text = $(this).text();
            $("#httpMethod").html(text + ' <span class="caret"></span>');
        });

        $('#parameter-tab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $(".parameter-post-list li,.tool-api-response .nav-tabs>li").on("click",function (e) {
            $(this).tab('show');
        });

        $(".parameter-post-list>li[href='#raw']").on("shown.bs.tab",function (e) {
            if(!window.RawEditor) {
                window.RawEditor = CodeMirror.fromTextArea(document.getElementById("demotext"), {
                    lineNumbers: true,
                    mode: "text/javascript",
                    matchBrackets: true,
                    indentUnit: 2,
                    autofocus: true,
                });
            }
        });

        $("#sendRequest").on("click",function () {
            var url = $("#requestUrl").val();
            if(!url){
                layer.msg("请输入一个URL");
            }
            var method = $("#httpMethod").text();
            var runApi = new RunApi();

            var header = runApi.resolveRequestHeader();
            var body = runApi.resolveRequestBody();

            runApi.send(url,method,header,body);
        });



        window.ResponseEditor = CodeMirror.fromTextArea(document.getElementById('responseBodyContainer'),{
            lineNumbers: true,
            mode: "text/html",
            readOnly : true,
            lineWrapping : true
        })

        $(".tool-api-menu-submenu>li>a,.tool-api-menu>li>a").on("click",function (e) {
            $(this).closest("li").toggleClass("open-menu");
        });
    });
</script>
</body>
</html>