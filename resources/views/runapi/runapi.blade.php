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
    <link href="/static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('static/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="/static/seltree/seltree.css" rel="stylesheet">

    <link href="{{asset('static/styles/styles.css')}}" rel="stylesheet">
    <link href="{{asset('static/bootstrap/icheck/skins/square/square.css')}}" rel="stylesheet">
    <link href="{{asset('static/codemirror/lib/codemirror.css')}}" rel="stylesheet">
    <link href="{{asset('static/styles/tool.css')}}" rel="stylesheet">
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
        .tool-api-menu-submenu>li>a{
            padding-left: 10px;
        }
        .tool-api-menu a{
            color: #505050;
            display: block;
            padding: 10px 0;
            text-decoration: none;
            border-bottom: 1px solid #DBDBDB;

        }
        .tool-api-menu>.open-menu>a{
            -webkit-box-shadow: 0 1px 5px #DBDBDB;
            -moz-box-shadow:0 1px 5px #DBDBDB;
            box-shadow: 0 1px 5px #DBDBDB;
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
        .tool-api-menu>li,.tool-api-menu-submenu>li,.api-items>li{
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
        .tool-api-item>i.fa{
            width: 15px;
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
        .tool-api-menu .tool-api-menu-submenu a>.fa{
            color: #B4B4B4;
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
        .tool-api-menu>.open-menu>a>i:before{
            content: "\f07c";
        }
        .tool-api-menu>.open-menu>.tool-api-menu-submenu>.open-menu>a>i:before{
                content: "\f115";
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
        .tool-api-menu .tool-api-menu-submenu>.open>.btn-group-more>.btn-more,
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
            width: 65px;
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
    <script type="text/javascript">
        window.config = {
            "ClassifyDeleteUrl" : "{{route('runapi.delete.classify')}}",
            "ClassifyEditUrl" : "{{route('runapi.edit.classify')}}",
            'ClassifyListUrl' : "{{route('runapi.classify.list')}}",
            'ApiSaveUrl' : "{{route('runapi.edit.api')}}",
            "ClassifyTreeUrl" : "{{route('runapi.classify.tree')}}",
            "ApiMetadataGetUrl" : "{{route('runapi.metadata.api')}}",
            "ApiMetadataSaveUrl" : "{{route('runapi.metadata.save.api')}}",
            "ApiDeleteUrl" : "{{route('runapi.delete.api')}}"
        };
    </script>
</head>
<body>
<div class="manual-reader">
    <header class="navbar navbar-static-top smart-nav navbar-fixed-top" role="banner">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="{{route('home.index')}}" class="navbar-brand"> {{wiki_config('SITE_NAME','SmartWiki')}}</a>
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
                <a href="###" class="pull-left tool-api-action"  data-toggle="modal" data-target="#editClassifyModal">
                    <i class="fa fa-folder"></i> 添加分类
                </a>
                <a href="javascript:;" class="pull-right tool-api-action" id="btnAddApi" style="border-left: 1px solid #DBDBDB;">
                    <i class="fa fa-plus"></i> 添加接口
                </a>
                <div class="clearfix"></div>
            </div>
            <div id="tool-api-classify-items">
                <ul class="tool-api-menu">
                    @if(empty($classify) === false && count($classify) > 0)
                        @foreach($classify as $item)
                            @include("runapi.classify", (array)$item)
                        @endforeach
                    @endif

                    <li>
                        <a href="###">
                            <i class="fa fa-folder"></i>
                            <div class="tool-api-menu-title">默认分类<br/><span class="text">0 个接口</span></div>
                        </a>
                        <div class="btn-group btn-group-more">
                            <button class="btn btn-more dropdown-toggle" style="height: 63px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-h"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-more">
                                <li><a href="###"><i class="fa fa-pencil"></i> 编辑</a></li>
                                <li><a href="###"><i class="fa fa-folder"></i> 添加分类</a> </li>
                                <li><a href="###"><i class="fa fa-trash"></i> 删除</a></li>
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
        </div>
        <div class="page-right">
            <div class="row">
                <button style="display: none;" id="chromeExtensionEventTriggerBtn"></button>
                <button class="hidden" id="chromeExtensionResponseEventTriggerBtn"></button>
                <textarea class="hidden" id="chromeExtensionResponse"></textarea>

                <form method="post" id="toolApiContainer" action="{{route("runapi.edit.api")}}">
                    @include("runapi.body")
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <!-- Add Api Classify Modal -->
    <div class="modal fade" id="editClassifyModal" tabindex="-1" role="dialog" aria-labelledby="editClassifyTitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" id="editClassifyForm" action="{{route("runapi.edit.classify")}}">
                    <input type="hidden" name="parentId" value="0">
                    <input type="hidden" name="classifyId" value="0">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="editClassifyTitle">编辑分类</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="classifyName">名称</label>
                            <input type="text" name="classifyName" class="form-control" value="" placeholder="分类名称" id="classifyName" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="description">描述</label>
                            <textarea name="description" class="form-control" style="resize: none;height: 150px;" placeholder="分类描述" id="description" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary" data-loading-text="保存中">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('runapi.metadata',['isForm'=>true])

    <script type="text/plain" id="parameterTemplate">
        @include("runapi.params")
    </script>
    <script type="text/html" id="apiViewTemplate">
       @include("runapi.body")
    </script>
</div>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="text/javascript" src="{{asset('static/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/bootstrap/icheck/icheck.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script type="text/javascript" src="/static/seltree/seltree.js"></script>
<script type="text/javascript" src="/static/scripts/jquery.form.js"></script>
<script type="text/javascript" src="/static/codemirror/lib/codemirror.js"></script>
<script src="/static/codemirror/mode/xml/xml.js"></script>
<script src="/static/codemirror/mode/javascript/javascript.js"></script>
<script src="/static/codemirror/mode/css/css.js"></script>
<script src="/static/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="/static/codemirror/addon/edit/matchbrackets.js"></script>
<script src="/static/scripts/json2.js"></script>
<script type="text/javascript" src="{{asset('static/scripts/runapi.js')}}"></script>
<script type="text/javascript">
    window.RawEditor = null;


    $(function () {
        window.renderParameter();

        $("#btnAddApi").on("click", window.newApiView);

        window.bindApiViewEvent();

        /**
         * 接口详情模态窗
         **/
        $("#toolApiContainer").on("shown.bs.modal","#saveApiModal", function () {
            $(this).find(".dropdown-select").selTree({});
        });

        /**
         * 编辑接口元数据
         **/
        $("#editApiForm").on("submit",function () {

            var $then = $(this);

            $(this).ajaxSubmit({
                beforeSubmit : function () {
                    var apiName = $then.find("input[name='apiName']").val();
                    if(apiName == undefined || apiName == ""){
                        layer.msg("接口名称不能为空");
                        $then.find("input[name='apiName']").focus();
                        return false;
                    }
                    $then.find("button[type='submit']").button("loading");
                },
                success : function (res) {
                    if(res.errcode === 0){
                        $("#api-item-" + res.data.api_id).replaceWith(res.data.view);

                    }else{
                        layer.msg(res.message);
                    }
                },
                complete : function () {
                    $then.find("button[type='submit']").button("reset");
                    $("#editApiModal").modal("hide");
                }
            });
            return false;
        });

        /**
         * 添加分类模态窗
         */
        $("#editClassifyModal").on("hidden.bs.modal", function () {
            var classify = new Classify();
            classify.resetClassifyForm();
        }).on("shown.bs.modal", function () {
            var classify = new Classify();
            classify.saveClassify();
        });

        /**
         * 编辑、删除、添加分类
         */
        $("#tool-api-classify-items").on("click", ".btn_classify_edit", function () {

            var id = $(this).closest("li[data-id]").attr("data-id");
            if (id) {
                var classify = new Classify();
                classify.editClassify(id);
            }
        }).on("click", ".btn_classify_del", function () {
            var $then = $(this);
            layer.confirm("删除分类会将该分类下所有子分类和接口都删除，你确定删除吗？", {
                btn: ['确定', '取消']
            }, function (index) {
                layer.close(index);
                var classify = new Classify();
                var id = $then.closest("li[data-id]").attr("data-id");
                if (id) {
                    window.loading = layer.load();
                    classify.delClassify(id);
                } else {
                    layer.msg("分类信息获取失败");
                }
            });
        }).on("click", ".btn_classify_add", function (e) {
            e.preventDefault();
            var id = $(this).closest("li[data-id]").attr("data-id");
            if (id) {
                $("#editClassifyModal").modal("show").find("input[name='parentId']").val(id);
            }
        }).on("click", ".tool-api-menu-submenu>li>a,.tool-api-menu>li>a", function (e) {
            //加载接口分类的子分类
            window.renderApiItem(this);
        }).on("click", ".tool-api-item", function () {
            //加载接口详情
            var id = $(this).closest("li[data-id]").attr("data-id");
            if (id) {
                window.loadApiView(id);
            }
        }).on("click",".btn_api_edit",function () {
            //当点击接口编辑按钮时

            var id = $(this).closest("li[data-id]").attr("data-id");
            var index = layer.load();
            var url = window.config.ApiMetadataGetUrl + '/' + id + ' .modal-body';

            $("#editApiModal .modal-body").load(url,function () {
                layer.close(index);
                $("#editApiModal").modal('show');
            });
        }).on("click",".btn_api_del",function () {
            //接口删除
            var $then = $(this).closest("li[data-id]");

            var id = $then.attr("data-id");

            $.ajax({
                url : window.config.ApiDeleteUrl,
                data : {"api_id" : id},
                type : "POST",
                dataType : "json",
                success : function (res) {
                    if(res.errcode === 0){
                        $then.remove().empty();
                    }else{
                        layer.msg(res.message);
                    }
                }
            });
        });

        //用于接收Chrome插件响应数据处理
        $("#chromeExtensionResponseEventTriggerBtn").on("click",function () {
            var responseText = $.trim($("#chromeExtensionResponse").text());
            if(responseText !== ""){
                var response =  jQuery.parseJSON(responseText);
                window.renderResponseView(response)
            }
        });
    });
</script>
</body>
</html>