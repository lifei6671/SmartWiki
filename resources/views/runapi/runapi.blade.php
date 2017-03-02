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
    <link href="{{asset('static/seltree/seltree.css')}}" rel="stylesheet">
    <link href="{{asset('static/editormd/css/editormd.min.css')}}" rel="stylesheet">

    <link href="{{asset('static/styles/styles.css')}}" rel="stylesheet">
    <link href="{{asset('static/bootstrap/icheck/skins/square/square.css')}}" rel="stylesheet">
    <link href="{{asset('static/codemirror/lib/codemirror.css')}}" rel="stylesheet">
    <link href="{{asset('static/styles/tool.css')}}" rel="stylesheet">
    <link href="{{asset('static/styles/markdown.css')}}" rel="stylesheet">
    <link href="{{asset('static/editormd/editormd.js')}}" rel="contents" id="editormdScript">
    <link href="{{asset('static/styles/runapi.css')}}" rel="stylesheet">

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
            "ApiDeleteUrl" : "{{route('runapi.delete.api')}}",
            "ClassifyShareUrl" : "{{route('runapi.share.api')}}"
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
    @include("runapi.markdown")
    @include("runapi.share")

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
<script type="text/javascript" src="{{asset('static/seltree/seltree.js')}}"></script>
<script type="text/javascript" src="{{asset('static/scripts/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{asset('static/codemirror/lib/codemirror.js')}}"></script>
<script src="{{asset('static/codemirror/mode/xml/xml.js')}}" type="text/javascript"></script>
<script src="{{asset('static/codemirror/mode/javascript/javascript.js')}}" type="text/javascript"></script>
<script src="{{asset('static/codemirror/mode/css/css.js')}}" type="text/javascript"></script>
<script src="{{asset('static/codemirror/mode/htmlmixed/htmlmixed.js')}}" type="text/javascript"></script>
<script src="{{asset('static/codemirror/addon/edit/matchbrackets.js')}}" type="text/javascript"></script>
<script src="{{asset('static/scripts/json2.js')}}" type="text/javascript"></script>
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
                        $("#api-item-7").empty().remove();


                        $("#tool-api-classify-items").find("li[data-id='"+res.data.classify_id+"']>.api-items").append(res.data.view);
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
            var url = window.config.ApiMetadataGetUrl + '/' + id;

            $.get(url,function (res) {
                var html = $(res).find(".modal-body").html();
                $("#editApiModal .modal-body").html(html);
                window.showSaveApiModal("#editApiModal",function () {
                    layer.close(index)
                });
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
        }).on("click",".btn_classify_share",function () {
            var id = parseInt($(this).closest("li[data-id]").attr("data-id"));
            var url = window.config.ClassifyShareUrl + "/" + id + " .modal-body-content";

            $("#shareRequestFolderModal").find(".modal-body").load(url,function () {
                $("#shareRequestFolderModal").modal("show");
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

        $("#shareRequestFolderModal").on("click",".close",function () {
            var $then = $(this);

            var account = $then.closest('.team-member-item').attr('data-account');
            var action = $("#shareRequestFolderForm").attr("action");
            var classify_id = $("#shareRequestFolderForm").find("input[name='classify_id']").val();
            var index = layer.load();

            $.ajax({
                url : action,
                type :"post",
                data : {"account" : account,"action" :"del","classify_id" : classify_id},
                dataType : "json",
                success : function (res) {
                    if(res.errcode === 0){
                        $then.closest(".team-member-item").remove().empty();
                    }else{
                        layer.msg(res.message);
                    }
                },
                complete : function () {
                    layer.close(index);
                }
            });

        });
        $("#shareRequestFolderForm").ajaxForm({
            beforeSubmit : function () {
                var account = $("#memberName").val();
                if(account == ""){
                    layer.msg('用户账号不能为空');
                    $("#memberName").focus();
                    return false;
                }
                var btn = $("#shareRequestFolderForm").find('button[type="submit"]');
                btn.button('loading');
            },
            success :function (res) {
                if(res.errcode === 0){
                    if(res.hasOwnProperty('data')){
                        $("#shareRequestFolderForm").find(".team-member-list").prepend(res.data.view);
                    }
                    $("#memberName").val('');
                }else{
                    layer.msg(res.message);
                }
            },
            complete : function () {
                $("#shareRequestFolderForm").find('button[type="submit"]').button('reset');
            }
        });
    });
</script>
</body>
</html>