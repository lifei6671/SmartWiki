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

                        window.ResponseEditor.setValue(data);
                    },
                    statusCode : {
                        404 : function () {

                        }
                    },
                    complete : function (xhr, textStatus) {
                        var time = (new Date()) - startTime;

                        $("#httpTime").text(time + 'ms');
                        $("#httpCode").text(xhr.status + ' ' + xhr.statusText);
                        window.ResponseEditor.setValue(xhr.responseText);
                    }
                });
            },
            resolveResponseHeader : function(header) {

            },
            resolveRequestBody : function () {
                var body = {};
                var type = $(".parameter-post-list input:checked").val();

                if(type === "x-www-form-urlencodeed"){
                    $("#x-www-form-urlencodeed>table>tbody>tr").each(function (index, domEle) {
                        var checkbox = $(domEle).find('input[type="checkbox"]').is(":checked");
                        if(checkbox){
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
            resolveRequestHeader : function () {
                var header = {};
                $("#headers>table>tbody>tr").each(function (index, domEle) {
                    var checkbox = $(domEle).find('input[type="checkbox"]').is(":checked");
                    if(checkbox){
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
                $("#editClassifyForm").ajaxForm({
                    dataType : "json",
                    beforeSubmit : function () {
                        var classifyName = $("#editClassifyForm").find("input[name='classifyName']").val();
                        if(classifyName === undefined || $.trim(classifyName) === ""){
                            alert("分类名称不能为空");
                            return false;
                        }
                    },
                    success : function (res) {
                        if(res.errcode === 0){
                            $("#editClassifyModal").modal('hide');
                            $("#tool-api-classify-items>.tool-api-menu").append(res.data);
                        }else{
                            alert(res.message);
                        }
                    },
                    error : function () {
                      alert("系统异常");
                    }
                });
          },
          delClassify : function (id) {
            $.post(window.config.ClassifyDeleteUrl,{"classifyId":id},function (res) {
                if(res.errcode === 0){
                    $("[data-id='"+id+"']").closest("li").remove().empty();
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

})(jQuery);