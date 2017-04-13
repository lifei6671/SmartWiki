<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SmartWiki" />
    <title>编辑文档 - {{wiki_config('SITE_NAME','SmartWiki')}}</title>
    <link href="{{asset('static/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/editormd/css/editormd.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/jstree/themes/default/style.css')}}" rel="stylesheet">

    <link href="{{asset('static/styles/wiki.css')}}" rel="stylesheet">
    <link href="{{asset('static/styles/wikiedit.css')}}" rel="stylesheet">
    <link href="{{asset('static/styles/markdown.css')}}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{asset('static/bootstrap/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('static/bootstrap/js/respond.min.js')}}"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('static/scripts/jquery.min.js')}}"></script>
    <script type="text/javascript">
        window.CONFIG = {
            "project_id" : "{{$project_id}}"
        };
        window.treeCatalog = {};
    </script>
</head>
<body>
<div id="manual-edit">
    <div id="tree-root" style="width: 300px;">
        <div class="nav-item-left">
            <i class="fa fa-th-large"></i> 目录
        </div>
        <div class="nav-item-right">
            <button data-target="#create-new" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="create-document" title="创建文档">
                <i class="fa fa-plus"></i>
            </button>
        </div>
        <div class="nav-item-content" id="sidebar" style="height:100%;overflow: auto">

        </div>

    </div>
    <form method="post" action="{{route('document.save')}}" id="form-editormd">
        <div class="editormd-body">
            <div id="editormd">
                <input type="hidden" name="doc_id" id="documentId">
                <textarea style="display:none;">### Hello Editor.md !</textarea>
            </div>
        </div>
    </form>
</div>
<!-- Modal -->
<div class="modal fade" id="create-wiki" tabindex="-1" role="dialog" aria-labelledby="添加文件" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" method="post" action="{{route('document.save')}}" id="form-document">
                <input type="hidden" name="project_id" value="{{$project_id or ''}}">
                <input type="hidden" name="id" value="{{$doc_id or ''}}">
                <input type="hidden" name="parentId" value="{{$parent_id or 0}}">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">添加文档</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="documentName" class="col-sm-2 control-label" id="inputTitle">名称</label>
                        <div class="col-sm-10">
                            <input type="text" name="documentName" class="form-control" id="documentName" placeholder="文档名称" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="error-message" style="color: #919191; font-size: 13px;"></span>
                    <button type="submit" class="btn btn-primary" id="btn-action" data-loading-text="提交中...">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="template-modal" tabindex="-1" role="dialog" aria-labelledby="请选择模板类型" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">请选择模板类型</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="section">
                        <a data-type="normal" href="javascript:;"><i class="fa fa-file-o"></i></a>
                        <h3><a data-type="normal" href="javascript:;">普通文档</a></h3>
                        <ul>
                            <li>默认类型</li>
                            <li>简单的文本文档</li>
                        </ul>
                    </div>
                    <div class="section">
                        <a data-type="api" href="javascript:;"><i class="fa fa-file-code-o"></i></a>
                        <h3><a data-type="normal" href="javascript:;">API文档</a></h3>
                        <ul>
                            <li>用于API文档速写</li>
                            <li>支持代码高亮</li>
                        </ul>
                    </div>
                    <div class="section">
                        <a data-type="code" href="javascript:;"><i class="fa fa-book"></i></a>

                        <h3><a data-type="code" href="javascript:;">数据字典</a></h3>
                        <ul>
                            <li>用于数据字典显示</li>
                            <li>表格支持</li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<script type="text/plain" id="template-normal">
@include("template.text")
</script>
<script type="text/plain" id="template-api">
@include("template.api")
</script>
<script type="text/plain" id="template-code">
@include("template.dictionary")
</script>
<script type="text/javascript" src="{{asset('static/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/jstree/jstree.js')}}"></script>
<script type="text/javascript" src="{{asset('static/scripts/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('static/scripts/json2.js')}}"></script>
<script type="text/javascript" src="{{asset('static/editormd/editormd.js')}}"></script>

<script type="text/javascript">
    /**
     * 初始化jstree
     */
    function initJsTree() {
        $("#sidebar").jstree({
            'plugins': [ "wholerow", "types", 'dnd', 'contextmenu'],
            "types": {
                "default": {
                    "icon": false  // 删除默认图标
                }
            },
            'core': {
                'check_callback': true,
                'data': {!! $json !!},
                'animation': 0,
                "multiple": false
            },
            "contextmenu": {
                show_at_node: false,
                select_node: false,
                "items": {
                    "添加文档": {
                        "separator_before": false,
                        "separator_after": true,
                        "_disabled": false,
                        "label": "添加文档",
                        "icon": "fa fa-plus",
                        "action": function (data) {

                            var inst = $.jstree.reference(data.reference),
                                node = inst.get_node(data.reference);

                            openCreateCatalogDialog(node);
                        }
                    },
                    "编辑": {
                        "separator_before": false,
                        "separator_after": true,
                        "_disabled": false,
                        "label": "编辑",
                        "icon": "fa fa-edit",
                        "action": function (data) {
                            var inst = $.jstree.reference(data.reference);
                            var node = inst.get_node(data.reference);
                            editDocumentDialog(node);
                        }
                    },
                    "删除": {
                        "separator_before": false,
                        "separator_after": true,
                        "_disabled": false,
                        "label": "删除",
                        "icon": "fa fa-trash-o",
                        "action": function (data) {
                            var inst = $.jstree.reference(data.reference);
                            var node = inst.get_node(data.reference);
                            deleteDocumentDialog(node);
                        }
                    }
                }
            }
        }).on('loaded.jstree', function () {
            window.treeCatalog = $(this).jstree();
            $select_node_id = window.treeCatalog.get_selected();
            if($select_node_id) {
                $select_node = window.treeCatalog.get_node($select_node_id[0])
                if ($select_node) {
                    $select_node.node = {
                        id: $select_node.id
                    };

                    window.loadDocument($select_node);
                }
            }

        }).on('select_node.jstree', function (node, selected, event) {
            if($("#markdown-save").hasClass('change')) {
                if(confirm("编辑内容未保存，需要保存吗？")){
                    $("#form-editormd").submit();
                }
            }
            window.loadDocument(selected);

        }).on("move_node.jstree", function (node, parent) {

            var parentNode = window.treeCatalog.get_node(parent.parent);

            var nodeData = window.getSiblingSort(parentNode);

            if (parent.parent != parent.old_parent) {
                parentNode = window.treeCatalog.get_node(parent.old_parent);
                var newNodeData = window.getSiblingSort(parentNode);
                if (newNodeData.length > 0) {
                    nodeData = nodeData.concat(newNodeData);
                }
            }

            var index = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });

            $.post("{{route('document.sort',["id" => $project_id])}}", JSON.stringify(nodeData)).done(function (res) {
                layer.close(index);
                if (res.errcode != 0) {
                    layer.msg(res.message);
                } else {
                    layer.msg("保存排序成功");
                }
            }).fail(function () {
                layer.close(index);
                layer.msg("保存排序失败");
            });
        });
    }
    $(function () {

        $("#template-modal .section>a").on("click",function () {
            var type = $(this).attr('data-type');
            if(type){
                var template = $("#template-" + type).text();
                window.editor.insertValue(template);
            }
            $("#template-modal").modal('hide');
        });
    });
</script>
<script type="text/javascript" src="{{asset('static/scripts/wiki.js')}}"></script>
</body>
</html>