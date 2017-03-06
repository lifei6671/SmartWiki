<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SmartWiki" />
    <title>{{$title}} - {{wiki_config('SITE_NAME','SmartWiki')}}</title>
    <!-- Bootstrap -->
    <link href="{{asset('static/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/highlight/styles/default.css')}}" rel="stylesheet">
    <link href="{{asset('static/highlight/styles/zenburn.css')}}" rel="stylesheet">
    <link href="{{asset('static/jstree/themes/default/style.css')}}" rel="stylesheet">
    <link href="{{asset('static/nprogress/nprogress.css')}}" rel="stylesheet">
    <link href="{{asset('static/styles/kancloud.css')}}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{asset('static/bootstrap/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('static/bootstrap/js/respond.min.js')}}"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('static/scripts/jquery.min.js')}}" type="text/javascript"></script>

</head>
<body>
<div class="m-manual manual-reader">

    <header class="navbar navbar-static-top manual-head" role="banner">
        <div class="container-fluid">
            <div class="navbar-header pull-left manual-title">
                <span class="slidebar" id="slidebar"><i class="fa fa-align-justify"></i></span>
                {{$project->project_name}}
                <span style="font-size: 12px;font-weight: 100;">v {{$project->version}}</span>
            </div>
            <div class="navbar-header pull-right manual-menu">
                <div class="dropdown">
                    <button id="dLabel" class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        项目
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dLabel">
                        @if($project->project_open_state ==1 || $project->project_open_state ==2)
                            <li><a href="javascript:" data-toggle="modal" data-target="#shareProject">项目分享</a> </li>
                            <li role="presentation" class="divider"></li>
                        @endif
                        <li><a href="{{route('document.export',['id' => $project->project_id])}}" target="_blank">项目导出</a> </li>
                        <li><a href="{{route('home.index')}}" title="返回首页">返回首页</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <article class="container-fluid manual-body">
        <div class="manual-left">
            <div class="manual-tab">
                <div class="tab-navg">
                    <span data-mode="view" class="navg-item active"><i class="fa fa-align-justify"></i><b class="text">目录</b></span>
                    {{--<span data-mode="search" class="navg-item"><i class="icon icon-search"></i><b class="text">搜索</b></span>--}}
                    {{--<span data-mode="collect" class="navg-item"><i class="icon icon-heart"></i><b class="text">收藏</b></span>--}}
                </div>
                <div class="tab-wrap">
                    <div class="tab-item manual-catalog">
                        <div class="catalog-list read-book-preview" id="sidebar">
                            {!! $tree !!}
                        </div>

                    </div>
                </div>
            </div>
            <div class="m-copyright">
                <p>
                    本文档使用
                    <a href="https://www.iminho.me" target="_blank">SmartWiki</a> 发布
                </p>
            </div>
        </div>
        <div class="manual-right">
            <div class="manual-article">
                <div class="article-head">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-8 text-center">
                                <h1 id="article-title">{{$title}}</h1>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                    </div>

                </div>
                <div class="article-content">
                    <div class="article-body editor-content"  id="page-content">
                        {!! $body !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="manual-progress"><b class="progress-bar"></b></div>
    </article>
    <div class="manual-mask"></div>
</div>

@if($project->project_open_state ==1 || $project->project_open_state ==2)
    <!-- Share Modal -->
    <div class="modal fade" id="shareProject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">项目分享</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">项目地址</label>
                        <div class="col-sm-10">
                            <input type="text" value="{{route('home.show',['id' => $project->project_id])}}" class="form-control" onmouseover="this.select()" id="projectUrl" title="项目地址">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="text/javascript" src="{{asset('static/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/jstree/jstree.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('static/nprogress/nprogress.js')}}"></script>
<script type="text/javascript" src="{{asset('static/highlight/highlight.js')}}"></script>
<script type="text/javascript" src="{{asset('static/highlight/highlightjs-line-numbers.min.js')}}"></script>
<script type="text/javascript">
    var events = $("body");
    var catalog = null;
    /**
     * 初始化高亮插件
     */
    function initHighlighting() {
        $('pre code').each(function (i, block) {
            hljs.highlightBlock(block);
        });

        hljs.initLineNumbersOnLoad();
    }
    $(function () {
        initHighlighting();

        var windowHeight = $(window).height();
        var bodyHeight = $(document).height();

        $(window).resize(function(){
            var windowHeight = $(window).height();
            var bodyHeight = $(document).height();
        });

        catalog = $("#sidebar").jstree({
            'plugins':["wholerow","types"],
            "types": {
                "default" : {
                    "icon" : false  // 删除默认图标
                }
            },
            'core' : {
                'check_callback' : false,
                "multiple" : false ,
                'animation' : 0
            }
        }).on('select_node.jstree',function (node,selected,event) {
            $(".m-manual").removeClass('manual-mobile-show-left');
            var url = selected.node.a_attr.href;

            if(url == window.location.href){
                return false;
            }
            $.ajax({
                url : url,
                type : "GET",
                beforeSend :function (xhr) {
                    var body = events.data('body_' + selected.node.id);
                    var title = events.data('title_' + selected.node.id);
                    var doc_title = events.data('doc_title_' + selected.node.id);

                    if(body && title && doc_title){

                        $("#page-content").html(body);
                        $("title").text(title);
                        $("#article-title").text(doc_title);

                        events.trigger('article.open',url,true);

                        return false;
                    }
                    NProgress.start();
                },
                success : function (res) {
                    if(res.errcode == 0){
                        var body = res.data.body;
                        var doc_title = res.data.doc_title;
                        var title = res.data.title;

                        $("#page-content").html(body);
                        $("title").text(title);
                        $("#article-title").text(doc_title);

                        events.data('body_' + selected.node.id,body);
                        events.data('title_' + selected.node.id,title);
                        events.data('doc_title_' + selected.node.id,doc_title);

                        events.trigger('article.open',url,false);

                    }else{
                        layer.msg("加载失败");
                    }
                },
                complete : function () {
                    NProgress.done();
                }
            });
        });

        $("#slidebar").on("click",function () {
            $(".m-manual").addClass('manual-mobile-show-left');
        });
        $(".manual-mask").on("click",function () {
            $(".m-manual").removeClass('manual-mobile-show-left');
        });
    });

    events.on('article.open', function (event, url,init) {
        if ('pushState' in history) {

            if (init == false) {
                history.replaceState({}, '', url);
                init = true;
            } else {
                history.pushState({}, '', url);
            }

        } else {
            location.hash = url;
        }
        initHighlighting();

    });
</script>
</body>
</html>