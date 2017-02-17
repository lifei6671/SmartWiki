/**
 * Created by lifeilin on 2017/2/13 0013.
 */

(function($){

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
                    success : function (data, textStatus, jqXHR) {
                        var headers = jqXHR.getAllResponseHeaders();

                        if(headers) {
                            headers = headers.split('\r');

                            var html = "";
                            for (index in headers) {
                                var item = headers[index].split(':');
                                if(item.length === 2) {
                                    html = html + '<p><strong>' + item[0] + '</strong>:' + item[1] + '</p>';
                                }
                            }
                            $("#responseHeader").html(html);
                        }

                        try{
                            var json = (new Function("return " + data))();

                            data = $this.formatJson(data);
                            window.ResponseEditor.setOption("mode","application/json");

                        }catch(e){
                            console.log(e);
                        }
                        console.log(data);
                        window.ResponseEditor.setValue(data);
                    },
                    statusCode : {
                        404 : function () {

                        }
                    },
                    complete : function (xhr, textStatus) {
                        $("#sendRequest").button("reset")
                        var time = (new Date()) - startTime;

                        $("#httpTime").text(time + 'ms');
                        $("#httpCode").text(xhr.status + ' ' + xhr.statusText);
                        window.ResponseEditor.setValue(xhr.responseText);
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
                    body = window.RawEditor.getValue();
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
            },
            formatJson : function(json, options) {
                var reg = null,
                    formatted = '',
                    pad = 0,
                    PADDING = '    '; // one can also use '\t' or a different number of spaces

                // optional settings
                options = options || {};
                // remove newline where '{' or '[' follows ':'
                options.newlineAfterColonIfBeforeBraceOrBracket = (options.newlineAfterColonIfBeforeBraceOrBracket === true) ? true : false;
                // use a space after a colon
                options.spaceAfterColon = (options.spaceAfterColon === false) ? false : true;

                // begin formatting...
                if (typeof json !== 'string') {
                    // make sure we start with the JSON as a string
                    json = JSON.stringify(json);
                } else {
                    // is already a string, so parse and re-stringify in order to remove extra whitespace
                    json = JSON.parse(json);
                    json = JSON.stringify(json);
                }

                // add newline before and after curly braces
                reg = /([\{\}])/g;
                json = json.replace(reg, '\r\n$1\r\n');

                // add newline before and after square brackets
                reg = /([\[\]])/g;
                json = json.replace(reg, '\r\n$1\r\n');

                // add newline after comma
                reg = /(\,)/g;
                json = json.replace(reg, '$1\r\n');

                // remove multiple newlines
                reg = /(\r\n\r\n)/g;
                json = json.replace(reg, '\r\n');

                // remove newlines before commas
                reg = /\r\n\,/g;
                json = json.replace(reg, ',');

                // optional formatting...
                if (!options.newlineAfterColonIfBeforeBraceOrBracket) {
                    reg = /\:\r\n\{/g;
                    json = json.replace(reg, ':{');
                    reg = /\:\r\n\[/g;
                    json = json.replace(reg, ':[');
                }
                if (options.spaceAfterColon) {
                    reg = /\:/g;
                    json = json.replace(reg, ': ');
                }

                $.each(json.split('\r\n'), function(index, node) {
                    var i = 0,
                        indent = 0,
                        padding = '';

                    if (node.match(/\{$/) || node.match(/\[$/)) {
                        indent = 1;
                    } else if (node.match(/\}/) || node.match(/\]/)) {
                        if (pad !== 0) {
                            pad -= 1;
                        }
                    } else {
                        indent = 0;
                    }

                    for (i = 0; i < pad; i++) {
                        padding += PADDING;
                    }

                    formatted += padding + node + '\r\n';
                    pad += indent;
                });

                return formatted;
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

    window.renderApiItem = function(e) {
        var liEle = $(this).closest("li[data-id]");
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
    }

    /**
     * 接口参数面板的事件处理和绑定
     */
    window.renderParameter = function() {
        //$(".parameter-active>tbody>tr .input-text").off("focus");

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
            $("#toolApiContainer").html(html);
            window.loadResponseEditor();
        };

        var changed = Number($("#isChangeForApi").val());

        console.log(Number(changed));
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

    window.loadResponseEditor = function () {
        /**
         * 初始化响应值的显示区域
         **/
        window.ResponseEditor = CodeMirror.fromTextArea(document.getElementById('responseBodyContainer'),{
            lineNumbers: true,
            mode: "text/html",
            readOnly : true,
            lineWrapping : true
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
        }).on("click",".parameter-post-list li,.tool-api-response .nav-tabs>li,#parameter-tab a",function () {
            $(this).tab('show');
        }).on("shown.bs.tab",".parameter-post-list>li[href='#raw']",function () {
            if(!window.RawEditor) {
                window.RawEditor = CodeMirror.fromTextArea(document.getElementById("demotext"), {
                    lineNumbers: true,
                    mode: "text/javascript",
                    matchBrackets: true,
                    indentUnit: 2,
                    autofocus: true,
                });
                window.RawEditor.on("change",function () {
                    $("#isChangeForApi").trigger("change.api");
                });
            }
        }).on("click","#sendRequest",function () {
            var url = $("#requestUrl").val();
            if(!url){
                layer.msg("请输入一个URL");
            }
            var method = $("#httpMethod").text();

            var header = runApi.resolveRequestHeader();
            var body = runApi.resolveRequestBody();

            runApi.send(url,method,header,body);
        }).on("change.api","#isChangeForApi",function () {
            $(this).val('1');
            $(".tool-api-title").find(".title>.fa").removeClass("saved");
        }).on("saved.api","#isChangeForApi",function () {
            $(this).val('0');
            $(".tool-api-title").find(".title>.fa").addClass("saved");
        }).on("click","#btnSaveApi",function () {
            var then = $("#toolApiContainer");

            var apiId = Number(then.find("input[name='api_id']").val());
            var apiName = $.trim(then.find("input[name='api_name']").val());
            var classifyId = Number(then.find("input[name='classify_id']").val());

            if(apiId || (apiName && classifyId)){

                var runApi = new window.RunApi();
                var header = runApi.resolveRequestHeader(true);
                var body = runApi.resolveRequestBody(true);

                $("#toolApiContainer").ajaxForm({
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
                        return true;
                    },
                    success : function (res) {
                        if(res.errcode === 0){
                            $("#editApiModal").modal("hide");
                            $("#isChangeForApi").trigger("saved.api");
                            $("#toolApiContainer input[name='api_id']").val(res.data.api_id);
                            $("#toolApiContainer .tool-api-title>h4>span").text(apiName);
                            $("#toolApiContainer .tool-api-title>.text").text(res.data.description);

                            var current = $("#tool-api-classify-items li[data-id='"+ res.data.api_id+"']");
                            console.log(current);
                            if(current.length > 0){
                                current.replaceWith(res.data.view);
                            }else{
                                $("#tool-api-classify-items li[data-id='"+ res.data.classify_id+"']").find(".api-items").append(res.data.view);
                            }

                        }
                    }
                });
            }else {
                var index = layer.load();
                $.ajax({
                    url : window.config.ClassifyTreeUrl,
                    success : function (res) {
                        if(res.errcode === 0){
                            var $then = $("#editApiModal").modal("show").find(".dropdown-select-menu").html(res.data.view);
                        }else{
                            layer.msg(res.message);
                        }
                    },
                    complete : function () {
                        layer.close(index);
                    }
                });

                return false;
            }
        }).on("keydown","#requestUrl",function (e) {
            $("#isChangeForApi").trigger("change.api");

        }).on("keydown",".input-text",function () {
            $("#isChangeForApi").trigger("change.api");
        });
    };

    window.loadApiDetailView = function ($id) {
        var index = layer.load();
        $.ajax({
           url : window.config.ApiSaveUrl + "/" + $id,
            type : "GET",
            success : function (res) {
                if(res.errcode === 0){
                    $("#editApiForm input[name='apiName']").val(res.data.api_name);

                    $.ajax({
                        url : window.config.ClassifyTreeUrl,
                        success : function (res) {
                            if(res.errcode === 0){
                                var $then = $("#editApiModal").modal("show").find(".dropdown-select-menu").html(res.data.view);
                            }else{
                                layer.msg(res.message);
                            }
                        },
                        complete : function () {
                            layer.close(index);
                        }
                    });
                }else{
                    layer.msg(res.message);
                }
            }
        });

    };
})(jQuery);

