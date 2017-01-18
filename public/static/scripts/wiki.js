
$(document).ready(function () {

    var width = $(window).width()-300;
    var height = $(window).height();


    $(".editormd-body").css('width',width + 'px').css('height',height + 'px');
    $("#tree-root .nav-item-content").css('height', (height - 100) + 'px');



    $(window).resize(function(){
        height = $(window).height();
        width = $(window).width()-300;
        var barHeight = $(".editormd-toolbar").height();
        $(".editormd-body").css('width',width + 'px').css('height',height + 'px');
        $("#tree-root .nav-item-content").css('margin-top',barHeight + 'px').css('height', (height - 100) + 'px');

    });

    $("#create-document").click(function () {
        openCreateCatalogDialog();
    });

    $("#create-document").tooltip({placement:"auto",placement : "left"});
    //弹出提示
    $("[data-toggle='tooltip']").tooltip();



});

(function (win) {

    win.isEditorChange = false;

    var $btn = $("#btn-action");
    var winTop = $(win.top || win), docTop = $(win.top.document);
    var $then = $("#create-wiki");
    var formError = $then.find('#error-message');
    var layerIndex;
    $then.on('shown.bs.modal',function () {
        $then.find("input[name='documentName']").focus();

    });
    $then.on("hidden.bs.modal",function () {
        $btn.button('reset');
    });


    //初始化编辑器
    win.editor = editormd("editormd", {
        path : "/static/editormd/lib/",
        placeholder: "本编辑器支持Markdown编辑，左边编写，右边预览",
        imageUpload: true,
        imageFormats: ["jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"],
        imageUploadURL: "/upload",
        fileUpload: true,
        fileUploadURL : '/upload',
        tocStartLevel : 1,
        tocm : true,
        toolbarIcons : [ "back","save", "template","undo", "redo" , "h1", "h2","h3" ,"h4","bold", "hr", "italic","quote","list-ul","list-ol","link","reference-link","image","file","code","html-entities","preformatted-text","code-block","table","history"],
        toolbarIconsClass : {
            bold : "fa-bold"
        } ,
        toolbarIconTexts :{
            bold : 'a'
        },
        toolbarCustomIcons:{
            back : '<a href="javascript:;" title="返回"> <i class="fa fa-mail-reply" name="back"></i></a>',
            save : '<a href="javascript:;" title="保存" id="markdown-save" class="disabled"> <i class="fa fa-save" name="save"></i></a>',
            history : '<a href="javascript:;" title="历史版本"> <i class="fa fa-history" name="history"></i></a>',
            template : '<a href="javascript:;" title="模板"> <i class="fa fa-tachometer" name="template"></i></a>'
        },
        toolbarHandlers :{
            /**
             * @param {Object}      cm         CodeMirror对象
             * @param {Object}      icon       图标按钮jQuery元素对象
             * @param {Object}      cursor     CodeMirror的光标对象，可获取光标所在行和位置
             * @param {String}      selection  编辑器选中的文本
             */
            back : function (cm, icon, cursor, selection) {
                if(document.referer){
                    window.history.back();
                }else{
                    window.location = '/member/projects';
                }

                return false;
            },
            save : function (cm, icon, cursor, selection) {
                if($("#markdown-save").hasClass('change')) {
                    $("#editormd-form").submit();
                }
            },
            history :function (cm, icon, cursor, selection) {
                var doc_id = $("#document-id").val();
                if(!doc_id){
                    layer.msg('当前文档暂无历史版本');
                }else{
                    layer.open({
                        type: 2,
                        title: '历史版本',
                        shadeClose: true,
                        shade: 0.8,
                        area: ['700px','80%'],
                        content: '/docs/history/'+doc_id,
                        end : function () {
                           // alert("a")
                            if(window.SelectedId){
                                var selected = {node:{
                                    id : window.SelectedId
                                }};
                                window.loadDocument(selected);
                                window.SelectedId = null;
                            }
                        }
                    });
                }
            },
            template : function(cm, icon, cursor, selection) {
                $("#template-modal").modal('show');
            }
        },
        onload : function () {
            editor.setToolbarAutoFixed(false);
            var index ;
            $(".editormd-menu>li>a").hover(function () {
                var title = $(this).attr('title');
                index = layer.tips(title,this,{
                    tips : 3
                });
            },function () {
                layer.close(index);
            });
            if(window.CONFIG.selected){
                window.loadDocument(window.CONFIG.selected);
            }
            initJsTree();
        },
        onchange : function () {
            if(win.isEditorChange) {
                win.isEditorChange = false;
            }else{
                $("#markdown-save").removeClass('disabled').addClass('change');
            }
        }
    });

    /**
     * 打开文档创建窗口
     * @param node
     */
    win.openCreateCatalogDialog = function (node) {
        var doc_id = node ? node.id : 0;

        $then.find("input[name='id']").val('');
        $then.find("input[name='parentId']").val(doc_id);
        $then.find("input[name='documentName']").val('');
        formError.text('');

        $then.modal({ show : true });
    };

    win.deleteDocumentDialog = function (node) {
        var index = layer.confirm('你确定要删除该文档吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){

            $.post("/docs/delete/" + node.id).done(function (res) {
                layer.close(index);
                if(res.errcode == 0){
                    win.treeCatalog.delete_node(node);
                    win.editor.clear();
                }else{
                    layer.msg("删除失败",{icon : 2})
                }
            }).fail(function () {
                layer.close(index);
               layer.msg("删除失败",{icon : 2})
            });

        });
    };

    win.editDocumentDialog = function (node) {
        var doc_id = node ? node.id : 0;
        var text = node ? node.text : '';
        var parentId = node && node.parent != '#' ? node.parent : 0;

        $then.find("input[name='id']").val(doc_id);
        $then.find("input[name='parentId']").val(parentId);
        $then.find("input[name='documentName']").val(text);
        formError.text('');

        $then.modal({ show : true });
    };

    win.getSiblingSort = function (node) {
        var data = [];

        for(key in node.children){
            var index = data.length;

            data[index] = {
                "id" : node.children[key],
                "sort" : key,
                "parent" : node.id
            };
        }
        return data;
    };
    //加载指定的文档
    win.loadDocument = function (selected) {
        var index = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });

        $.get("/docs/content/" + selected.node.id + '?dataType=json').done(function (data) {
            layer.close(index);
            $("#editormd-form").find("input[name='doc_id']").val(selected.node.id);
            window.editor.setValue("");
            if(data.errcode == 0 && data.data.doc.content){
                window.editor.insertValue(data.data.doc.content);
                window.editor.setCursor({line:0, ch:0});
                win.isEditorChange = true;
            }
        }).fail(function () {
            layer.close(index);
            layer.msg("加载文档失败");
        });
    };
    /**
     * 实现添加文档
     */
    $then.find("form").ajaxForm({
        type : "post",
        dataType : "json",
        beforeSubmit : function (formData, jqForm, options) {
            var name = $(jqForm).find("input[name='documentName']").val();
            var id = $(jqForm).find("input[name='id']").val();
            var node = win.treeCatalog.get_node(id);
            if(name == ""){
                formError.text('文档名称不能为空');
                return false;
            }
            if(node && node.text == name){
                $then.modal('hide');
                return false;
            }
            $btn.button('loading');
            return true;
        },
        success : function (res, statusText, xhr, $form) {
            $btn.button('reset')
            if(res.errcode == 0) {
                var data = { "id" : res.data.doc_id,'parent' : res.data.parent_id,"text" : res.data.name};

                var node = win.treeCatalog.get_node(data.id);
                if(node){
                    win.treeCatalog.rename_node({"id":data.id},data.text);
                }else {
                    var result = win.treeCatalog.create_node(res.data.parent_id, data, 'last');
                    win.treeCatalog.deselect_all();
                    win.treeCatalog.select_node(data);
                    win.editor.clear();
                }
                $("#markdown-save").removeClass('change').addClass('disabled');
                $then.modal('hide');
            }else{
                formError.text(res.message);
            }
        }
    });
    /**
     * 实现保存文档编辑
     */
    $("#editormd-form").ajaxForm({
        dataType:"json",
        beforeSubmit:function (formData, jqForm, options) {
            $("#markdown-save").removeClass('change').addClass('disabled');
            var content = $.trim(win.editor.getMarkdown());
            var id = $(jqForm).find("input[name='doc_id']").val();
            console.log(id);
            if(content == ""){
                layer.msg("保存成功");
                return false;
            }
            if(!id){
                layer.msg("没有需要保存的文档");
                return false;
            }
            layerIndex = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        },
        success :function (res) {
            if(res.errcode == 0){
                $("#markdown-save").removeClass('change').addClass('disabled');
                layer.close(layerIndex);
                layer.msg("文档已保存");
            }else{
                $("#markdown-save").removeClass('disabled').addClass('change');
                layer.msg(res.message);
            }
        }
    });

})(window);