<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title}} - {{wiki_config('SITE_NAME','SmartWiki')}}</title>
    <!-- Bootstrap -->
    <link href="/static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/static/highlight/styles/atelier-savanna-light.css" rel="stylesheet">
    <link href="/static/jstree/themes/default/style.css" rel="stylesheet">
    <link href="/static/nprogress/nprogress.css" rel="stylesheet">
    <link href="/static/styles/styles.css" rel="stylesheet">
    <link href="/static/styles/wiki.css" rel="stylesheet">
    <link href="/static/styles/markdown.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/static/bootstrap/js/html5shiv.min.js"></script>
    <script src="/static/bootstrap/js/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/scripts/jquery.min.js"></script>

</head>
<body>
<header class="navbar navbar-static-top smart-nav wiki-nav" role="banner">
    <div class="container">
        <div class="navbar-header wiki-title">
            {{$project->project_name}}
            <span style="font-size: 12px;">v {{$project->version}}</span>
        </div>

    </div>
</header>
<article class="container wiki-container">
    <div class="row">
        <div class="col-lg-3" id="sidebar">
            {!! $tree !!}
        </div>
        <div class="col-lg-9 col-md-8 col-sm-7" id="page-content">
            <div class="article-content">
                <div id="page-title">
                    <h1>{{$title}}</h1>
                </div>
                <div class="markdown-body">
                    {!! $body !!}
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<script src="/static/scripts/stickUp.min.js"></script>
<script type="text/javascript" src="/static/jstree/jstree.min.js"></script>
<script type="text/javascript" src="/static/layer/layer.js"></script>
<script type="text/javascript" src="/static/nprogress/nprogress.js"></script>
<script type="text/javascript" src="/static/highlight/highlight.js"></script>
<script type="text/javascript">

    $(function () {
        $('pre>code').each(function(i, block) {
            hljs.highlightBlock(block);
        });

        $(document).ready(function () {
            $('#sidebar>ul').stickUp({
                marginTop:"5px"
            });
        })
        var windowHeight = $(window).height();
        var bodyHeight = $(document).height();
        var height = Math.max(windowHeight,bodyHeight);

        $("#sidebar").css('height',height + 'px');
        $(window).resize(function(){
            var windowHeight = $(window).height();
            var bodyHeight = $(document).height();
            var height = Math.max(windowHeight,bodyHeight);
            $("#sidebar").css('height',height + 'px');
        });

        $("#sidebar").jstree({
            'plugins':["wholerow","types"],
            "types": {
                "default" : {
                    "icon" : false  // 删除默认图标
                },
            },
           'core' : {
               'check_callback' : false,
               "multiple" : false ,
               'animation' : 0
           }
        }).on('select_node.jstree',function (node,selected,event) {

            var url = selected.node.a_attr.href;

            if(url == window.location.href){
                return false;
            }
            $.ajax({
               url : url,
                type : "GET",
                beforeSend :function (xhr) {
                    var body = $("body").data('body_' + selected.node.id);
                    var title = $("body").data('title_' + selected.node.id);
                    var doc_title = $("body").data('doc_title_' + selected.node.id);

                    if(body && title && doc_title){

                        $("#page-content .markdown-body").html(body);
                        $("#page-title h1").text(doc_title);
                        $("title").text(title);

                        if(history.pushState){
                            history.pushState({ title: doc_title }, doc_title, url);
                        }
                        return false;
                    }
                    NProgress.start();
                },
                success : function (res) {
                    if(res.errcode == 0){
                        var body = res.data.body;
                        var doc_title = res.data.doc_title;
                        var title = res.data.title;

                        $("#page-content .markdown-body").html(body);

                        $("#page-title h1").text(doc_title);
                        $("title").text(title);

                        $("body").data('body_' + selected.node.id,body);
                        $("body").data('title_' + selected.node.id,title);
                        $("body").data('doc_title_' + selected.node.id,doc_title);
                        if(history.pushState){
                            history.pushState({ title: doc_title }, doc_title, url);
                        }
                    }else{
                        layer.msg("加载失败");
                    }
                },
                complete : function () {
                    NProgress.done();
                }
            });
        });
    })
</script>
</body>
</html>