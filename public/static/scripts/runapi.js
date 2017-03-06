/**
 * Created by lifeilin on 2017/2/13 0013.
 */

(function($){

    window.renderResponseView = function (response) {
        $("#sendRequest").button("reset")

        if(response.hasOwnProperty("headers")) {
            var html = "";
            for (var index in response.headers) {

                var item = response.headers[index];

                for(var key in item){
                    html = html + '<p><strong>' + key + '</strong>:' + item[key] + '</p>';
                }
            }

            $("#responseHeader").html(html);
        }

        if(response.hasOwnProperty("cookies")){
            var html = "";
            for (var index in response.cookies){
                var cookie = response.cookies[index];
               // alert(cookie)
                var time = '';
                if(cookie.hasOwnProperty("expirationDate")){
                    var date = new Date();
                    date.setTime(cookie.expirationDate * 1000);
                    time = date.toLocaleString();
                }
                html += "<tr><td>" + cookie.name +
                    "</td><td>" + cookie.value +
                    "</td><td>" + cookie.domain +
                    "</td><td>" + cookie.path +
                    "</td><td>" + time +
                    "</td><td>" + cookie.httpOnly +
                    "</td><td>" + cookie.secure +
                    "</td></tr>";
            }
            //alert(html)
            $("#responseCookie>table>tbody").html(html);
        }

        $("#httpTime").text(response.time + 'ms');
        $("#httpCode").text(response.status + ' ' + response.statusText);

        try{
            response.responseText = JSON.stringify(JSON.parse(response.responseText), null, 4);
            window.ResponseEditor.setOption("mode","application/ld+json");
        }catch(e){
            console.log(e);
        }

        window.ResponseEditor.setValue(response.responseText);
    };

    /**
     * 负责发起请求
     * @returns {{sendBefore: sendBefore, send: send, resolveResponseHeader: resolveResponseHeader, resolveRequestBody: resolveRequestBody, resolveRequestHeader: resolveRequestHeader}}
     * @constructor
     */
    window.RunApi = function () {
        var header = {};
        var startTime = null;

        return {
            sendBefore : function () {
                window.ResponseEditor.setValue('');
                $("#responseCookie").children("tbody").html('');
                $("#responseHeader").text('');
                $("#httpCode").text('');
                $("#httpTime").text('');
                $("#sendRequest").button("loading");
                startTime = new Date();
            },
            send : function (url,method, header, body) {
                var $this = this;
                var contentType = getRequestContentType(header);
                method = method ? $.trim(method).toLowerCase() : 'get';


                $.ajax({
                    url : url,
                    type : method,
                    cache : false,
                    contentType : contentType,
                    headers : header,
                    crossDomain: true,
                    xhrFields : { withCredentials : true },
                    data : body,
                    dataType : "text",
                    beforeSend : function () {
                        $this.sendBefore();
                    },
                    error : function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown);

                    },
                    complete : function (xhr, textStatus) {
                        // console.log(xhr)
                        var rawHeaders = xhr.getAllResponseHeaders();
                        var headers = rawHeaders.split('\n');
                        var unpackedHeaders = [];

                        for (var index in headers) {
                            var item = headers[index].split(':');
                            if(item.length === 2){
                                var node = {};
                                node[item[0]] = item[1];
                                unpackedHeaders.push(node);
                            }
                        }
                        var response = {
                            time : (new Date()) - startTime,
                            responseType: xhr.responseType,
                            readyState : xhr.readyState,
                            rawHeaders: rawHeaders,
                            headers: unpackedHeaders,
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText : xhr.responseText
                        };
                        window.renderResponseView(response);
                    }
                });
            },
            resolveResponseHeader : function(header) {

            },
            resolveRequestBody : function (isAll) {
                var body = {};
                var type = $(".parameter-post-list input:checked").val();

                if(type === "x-www-form-urlencodeed" || isAll){
                    $("#x-www-form-urlencodeed>table>tbody>tr").each(function (index, domEle) {
                        var checkbox = $(domEle).find('input[type="checkbox"]').is(":checked");
                        if(checkbox || isAll){
                            var key = $(domEle).find("input[name='key']").val();
                            if(key && key !== ""){
                                body[key] = $(domEle).find("input[name='value']").val();
                            }
                        }
                    });
                }else{
                    if(window.RawEditor != null){
                        body = window.RawEditor.getValue();
                    }else{
                        body = $("#rawModeData").val();
                    }
                }

                return body;
            }
            ,
            resolveRequestHeader : function (isAll) {
                var header = {};
                $("#headers>table>tbody>tr").each(function (index, domEle) {
                    var checkbox = $(domEle).find('input[type="checkbox"]').is(":checked");
                    if(checkbox || isAll){
                        var key = $(domEle).find("input[name='key']").val();
                        if(key && key !== ""){
                            header[key] = $(domEle).find("input[name='value']").val();
                        }
                    }
                });

                return header;
            }
        };
    };

    /**
     * 解析请求的 ContentType
     * @param header
     * @returns {string}
     */
    function getRequestContentType(header) {
        var contentType = 'application/x-www-form-urlencoded';
        if(header) {
            if (header.hasOwnProperty('contentType')) {
                contentType = header.contentType;
            }
            if (header.hasOwnProperty('ContentType')) {
                contentType = header.ContentType;
            }
        }
        return contentType;
    }


    window.Classify = function () {
      return{
          resetClassifyForm : function () {
              var classifyForm = $("#editClassifyForm");
              classifyForm.find("input[name='parentId']").val('0');
              classifyForm.find("input[name='classifyId']").val('0');
              classifyForm.find("input[name='classifyName']").val('');
              classifyForm.find("[name='description']").val("");
          },
          saveClassify : function () {
              var $then =  $("#editClassifyForm");

                $then.ajaxForm({
                    dataType : "json",
                    beforeSubmit : function () {
                        var classifyName = $then.find("input[name='classifyName']").val();
                        if(classifyName === undefined || $.trim(classifyName) === ""){
                            layer.msg("分类名称不能为空");
                            return false;
                        }
                        $then.find("button[type='submit']").button('loading');
                    },
                    success : function (res) {
                        if(res.errcode === 0){
                            $("#editClassifyModal").modal('hide');
                            //如果是编辑
                            if(res.data.is_edit){
                                $("li[data-id='"+ res.data.classify_id+"']").replaceWith(res.data.view);
                            }else if(res.data.parent_id > 0){
                                var el = $("#tool-api-classify-items")
                                    .find("li[data-id='"+res.data.parent_id+"']")
                                    .addClass("open-menu")
                                    .find(".tool-api-menu-submenu").addClass("open-menu").append(res.data.view);

                                console.log(el.html());

                            }else{
                                $("#tool-api-classify-items>.tool-api-menu").append(res.data.view);
                            }
                        }else{
                            layer.msg(res.message);
                        }
                    },
                    error : function () {
                      alert("系统异常");
                    },
                    complete : function () {
                        $then.find("button[type='submit']").button('reset');
                    }
                });
          },
          delClassify : function (id) {
            $.post(window.config.ClassifyDeleteUrl,{"classifyId":id},function (res) {
                layer.close(window.loading);
                if(res.errcode === 0){
                    $("[data-id='"+id+"']").remove().empty();
                }else{
                    layer.msg(res.message);
                }
            },"json");
          },
          editClassify : function (id) {

              $.get(window.config.ClassifyEditUrl + "/" + id,function (res) {
                  if(res.errcode === 0){
                      var $then = $("#editClassifyForm");
                      $then.find("input[name='classifyName']").val(res.data.classify_name);
                      $then.find("[name='classifyName']").val(res.data.classify_name);
                      $then.find("input[name='classifyId']").val(res.data.classify_id)
                      $("#editClassifyModal").modal("show");
                  }else{
                      layer.msg(res.errmsg);
                  }
              },"json");
          }
      }
    };

    window.renderApiItem = function($this) {
        var liEle = $($this).closest("li[data-id]");
        if(liEle.hasClass("open-menu")){
            liEle.removeClass("open-menu");
        }else {
            var id = liEle.attr("data-id");
            if(id){
                var isLoad = liEle.data('view.' + id);

                if(isLoad){
                    liEle.addClass("open-menu");
                }else {
                    var index = layer.load();

                    $.get(window.config.ClassifyListUrl + "/" + id, function (res) {
                        if (res.errcode === 0) {
                            view = res.data.view;
                            apiview = res.data.api_view;

                            liEle.addClass("open-menu").find(".tool-api-menu-submenu").html(res.data.view);
                            liEle.find(".api-items").html(res.data.api_view);
                            liEle.data('view.' + id, true);

                        } else {
                            layer.msg("获取分类ID时出错");
                        }
                        layer.close(index);
                    }, "json");
                }

            }else{
                layer.msg("获取分类ID时出错");
            }
        }
    };

    /**
     * 接口参数面板的事件处理和绑定
     */
    window.renderParameter = function() {
        $("#toolApiContainer").on("focus",".parameter-active>tbody>tr:last-child .input-text",function(e){
            e.preventDefault();

            var html = $("#parameterTemplate").html();
            var $then = $(this).closest('tbody');

            $then.find("tr .hide").removeClass("hide");
            $then.append(html);
            $('input:checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                increaseArea: '10%'
            });
           // renderParameter();
            $("#isChangeForApi").trigger("change.api");
        }).on("click",".parameter-close",function () {
            $(this).closest("tr").empty().remove();
            $("#isChangeForApi").trigger("change.api");
        });
    };

    window.newApiView = function (e) {

        var fun = function () {
            var html = $("#apiViewTemplate").html();
            window.RawEditor = null;
            $("#toolApiContainer").html(html);
            window.loadResponseEditor();
        };

        var changed = Number($("#isChangeForApi").val());

        if(!!changed){
            layer.confirm("当前接口还未保存，如果新添加接口会丢失未保存的数据，确定新添加吗？",{
                btn : ['确定',"保存并添加",'取消']
            },function (index) {
                fun();
                layer.close(index);
            },function (index) {
                $("#btnSaveApi").trigger("click");
                layer.close(index);
                fun();
            });
            return false;
        }
        fun();
        return true;
    };
    
    window.loadApiView = function (id) {

        id = Number(id);

        if(!id){
            layer.msg("获取接口ID失败");
            return false;
        }
        var loadViewFun = function () {
            var index = layer.load();
            $.ajax({
                url : window.config.ApiSaveUrl + '/' + id,
                data :{"dataType":"html"},
                type : "GET",
                dataType :"json",
                success :function (res) {
                    if(res.errcode === 0){
                        window.RawEditor = null;
                        $("#toolApiContainer").html(res.data.view);
                        window.loadResponseEditor();
                        $('input:checkbox').iCheck({
                            checkboxClass: 'icheckbox_square',
                            increaseArea: '10%'
                        });
                    }else{
                        layer.msg(res.message);
                    }
                },error : function () {
                    layer.msg("请求失败");
                },
                complete : function () {
                    layer.close(index);
                }
            });
        };

        var changed = Number($("#isChangeForApi").val());
        if(!!changed){
            layer.confirm("当前接口还未保存，如果新添加接口会丢失未保存的数据，确定新添加吗？",{
                btn : ['确定',"保存并添加",'取消']
            },function (index) {
                loadViewFun();
                layer.close(index);
            },function (index) {
                $("#btnSaveApi").trigger("click");
                layer.close(index);
                loadViewFun();
            });
            return false;
        }
        loadViewFun();
        return true;
    };

    /**
     * 初始化响应值的显示区域
     */
    window.loadResponseEditor = function () {
        window.ResponseEditor = CodeMirror.fromTextArea(document.getElementById('responseBodyContainer'),{
            lineNumbers: true,
            mode: "text/html",
            readOnly : true,
            lineWrapping : true,
            matchBrackets: true,
            autoCloseBrackets: true,
        });
        $("#chromeExtensionEventTriggerBtn").trigger("click")
    };
    /**
     * 加载 Raw 区域编辑器
     */
    window.loadRawEditor = function () {
        if(!window.RawEditor) {
            window.RawEditor = CodeMirror.fromTextArea(document.getElementById("rawModeData"), {
                lineNumbers: true,
                mode: "text/html",
                matchBrackets: true,
                indentUnit: 2,
                autofocus: true
            });
            window.RawEditor.on("change",function () {
                $("#isChangeForApi").trigger("change.api");
                $("#rawModeData").val(window.RawEditor.getValue());

            });
        }
    };
    window.sendApiRequest = function (e) {
        var url = $("#requestUrl").val();
        if(!url){
            layer.msg("请输入一个URL");
        }
        var method = $("#httpMethod").text();
        var runApi = new window.RunApi();
        var header = runApi.resolveRequestHeader();
        var body = runApi.resolveRequestBody();

        runApi.send(url,method,header,body);
    };
    /**
     * 弹出接口元数据窗口
     */
    window.showSaveApiModal = function ($id,callback ) {
        $.ajax({
            url : window.config.ClassifyTreeUrl,
            success : function (res) {
                if(res.errcode === 0){

                    $($id).find(".dropdown-select-menu").html(res.data.view);
                    $($id).find(".dropdown-select").selTree({});

                    $($id).modal("show");
                }else{
                    layer.msg(res.message);
                }
            },
            complete : function () {
                if(callback !== null && typeof callback === "function"){
                    callback();
                }
            }
        });
    };
    /***
     * API区域事件绑定和初始化
     */
    window.bindApiViewEvent = function () {
        var runApi = new window.RunApi();
        window.loadResponseEditor();

        $("#toolApiContainer").on("click","#btn-http-group .dropdown-menu>li",function (e) {
            e.preventDefault();
            var text = $(this).text();
            $("#httpMethod").html(text + ' <span class="caret"></span>');
            $("#toolApiContainer").find("input[name='http_method']").val(text);

            $("#isChangeForApi").trigger("change.api");
        }).on("click",".parameter-post-list li,.tool-api-response .nav-tabs>li,#parameter-tab>li",function () {
            $(this).tab('show');
        }).on("shown.bs.tab",".parameter-post-list>li[href='#raw']",function () {
            window.loadRawEditor();
        }).on("shown.bs.tab","#parameter-tab>li[href='#body']",function () {
            var value = $(".parameter-post-list>li[href='#raw'] input:checked").val();
            if(value == "raw"){
                window.loadRawEditor();
            }
        }).on("click","#sendRequest",window.sendApiRequest).on("change.api","#isChangeForApi",function () {
            $(this).val('1');
            $("#btnSaveApi").removeAttr("disabled")
            $(".tool-api-title").find(".title>.fa").removeClass("saved");
        }).on("saved.api","#isChangeForApi",function () {
            $(this).val('0');
            $(".tool-api-title").find(".title>.fa").addClass("saved");
            $("#btnSaveApi").attr("disabled","disabled")
        }).on("keydown","#requestUrl",function (e) {
            $("#isChangeForApi").trigger("change.api");

        }).on("keydown",".input-text",function () {
            $("#isChangeForApi").trigger("change.api");
        }).on("submit",function () {

            var then = $(this);

            var apiId = Number(then.find("input[name='apiId']").val());
            var apiName = $.trim(then.find("input[name='apiName']").val());
            var classifyId = Number(then.find("input[name='classifyId']").val());
            var request_url = $.trim(then.find("input[name='request_url']").val());

            if(request_url === ""){
                layer.msg("接口链接不能为空");
                then.find("input[name='request_url']").focus();
                return false;
            }

            //如果是已保存过的则直接保存否则弹出接口元数据窗口
            if(apiId> 0 || (apiName !== "" && classifyId > 0)){

                var header = window.resolveRequestHeader();
                var body = window.resolveRequestBody();

                $("#toolApiContainer").ajaxSubmit({
                    data :{
                        "http_header" : header,
                        "http_body" : body,
                        "raw_data" : window.RawEditor !== null ? window.RawEditor.getValue() : ""
                    },
                    beforeSubmit: function () {
                        var $then = $("#editApiForm");

                        if(apiId <= 0 && apiName === ""){
                            layer.msg("接口名称不能为空");
                            $then.find("input[name='apiName']").focus();
                            return false;
                        }
                        if(apiId <=0 && classifyId <= 0){
                            layer.msg("接口分类不能为空");
                            return false;
                        }
                        $("#btnSaveApi").button("loading");
                        return true;
                    },
                    success : function (res) {
                        if(res.errcode === 0){
                            $("#saveApiModal").modal("hide");
                            $("#isChangeForApi").trigger("saved.api");
                            $("#toolApiContainer input[name='api_id']").val(res.data.api_id);
                            $("#toolApiContainer .tool-api-title>h4>span").text(apiName);
                            $("#toolApiContainer .tool-api-title>.text").text(res.data.description);

                            $("#api-item-" + res.data.api_id).empty().remove();
                            $("#tool-api-classify-items li[data-id='"+ res.data.classify_id+"']>.api-items").append(res.data.view);


                        }else{
                            layer.msg(res.message);
                        }
                    },
                    complete:function () {
                        $("#saveApiModal button[type='submit']").button("rest");
                        $("#btnSaveApi").button("reset");
                        setTimeout(function () {
                            $("#btnSaveApi").attr("disabled","disabled");
                        });

                    }
                });
            }else {
                var index = layer.load();
                window.showSaveApiModal("#saveApiModal",function () {
                    layer.close(index);
                });

                return false;
            }
            return false;
        }).on("click","#saveApiModal button[type='submit']",function () {

            var then = $("#toolApiContainer");
            var apiName = $.trim(then.find("input[name='apiName']").val());
            var classifyId = Number(then.find("input[name='classifyId']").val());
            if(apiName === ""){
                layer.msg("接口名称不能为空");
                return false;
            }
            if(classifyId <= 0){
                layer.msg("请选择接口分类");
                return false;
            }
            $(this).button("loading");
            return true;
        }).on("click","#makeMarkdown",function () {
            var then = $("#makeMarkdownModal");

            var $this = $("#toolApiContainer");

            var url = then.find("form").attr("action");
            var apiId = Number($this.find("input[name='apiId']").val());
            var apiName = $.trim($this.find("input[name='apiName']").val());
            var classifyId = Number($this.find("input[name='classifyId']").val());
            var request_url = $.trim($this.find("input[name='request_url']").val());
            var header = window.resolveRequestHeader();
            var body = window.resolveRequestBody();
            var apiDescription = $this.find("#apiDescription").val();

            var data ={};

            data.http_header = header;
            data.http_body = body;
            data.raw_data = window.RawEditor !== null ? window.RawEditor.getValue() : "";
            data.apiName = apiName;
            data.apiDescription = apiDescription;
            data.classifyId = classifyId;
            data.request_url = request_url;
            data.parameterType = $this.find("input[name='parameterType']:checked").val();
            data.response = window.ResponseEditor !== null ? window.ResponseEditor.getValue() : '';

            console.log(data);

            $.post(url,data,function (res) {
                then.find(".modal-body").html('<div id="editormdContainer"></div>');
                then.modal("show");

                then.on("shown.bs.modal",function () {
                    var editormdScript = $("#editormdScript").attr("href");

                    $.getScript(editormdScript,function () {
                        window.markdowEditor = editormd("editormdContainer", {
                            path : "/static/editormd/lib/",
                            markdown : res,
                            watch : true,
                            syncScrolling : true,
                            placeholder: "本编辑器支持Markdown编辑，左边编写，右边预览",
                            width : "100%",
                            height : $(document).height() - 250,
                            toolbarIcons : [ "undo", "redo" , "h1", "h2","h3" ,"h4","bold", "hr", "italic","quote","list-ul","list-ol","link","reference-link","code","html-entities","preformatted-text","code-block","table"]
                        });
                    });


                });

            });


            then.on("hidden.bs.modal",function () {
               if(window.markdowEditor){
                   window.markdowEditor.editor.remove();
               }
            });

        }).on("click","#editAndSave",function () {
            var index = layer.load();
            window.showSaveApiModal("#saveApiModal",function () {
                layer.close(index)
            });
        });
    };

    /**
     * 解析保存到数据库的请求头信息
     * @returns {Array}
     */
    window.resolveRequestHeader = function () {
        var header = [];
        $("#headers>table>tbody>tr").each(function (index, domEle) {
            if($(domEle).find("label").hasClass("hide")){
                return;
            }
            var checkbox = $(domEle).find('input[type="checkbox"]').is(":checked");
            var key = $(domEle).find("input[name='key']").val();
            var value = $(domEle).find("input[name='value']").val();

            var item = {};
            item['key'] = key;
            item['value'] = value;
            item['enabled'] = checkbox;

            header.push(item);
        });

        return header;
    };
    /**
     * 解析保存到数据库的请求参数内容
     * @returns {Array}
     */
    window.resolveRequestBody = function () {
        var body = [];

        $("#x-www-form-urlencodeed>table>tbody>tr").each(function (index, domEle) {
            if($(domEle).find("label").hasClass("hide")){
                return;
            }
            var checkbox = $(domEle).find('input[type="checkbox"]').is(":checked");
            var key = $(domEle).find("input[name='key']").val();
            var value =  $(domEle).find("input[name='value']").val();
            var item = {};
            item['key'] = key;
            item['value'] = value;
            item['enabled'] = checkbox;
            item['type'] = 'text';

            body.push(item);
        });
        return body;
    };

})(jQuery);

