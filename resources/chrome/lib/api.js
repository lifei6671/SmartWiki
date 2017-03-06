$(function () {
    console.log(window.sendApiRequest)
});

(function ($) {
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
    var RunApi = function () {
        var header = {};
        var startTime = null;

        return {
            sendBefore : function () {
               // window.ResponseEditor.setValue('');
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
                    dataType : "text",
                    beforeSend : function () {
                        $this.sendBefore();
                    },
                    error : function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown);

                    },
                    complete : function (xhr, textStatus) {
                        console.log(xhr);
                        //$("#sendRequest").button("reset")

                        var headers = xhr.getAllResponseHeaders();

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

                        var time = (new Date()) - startTime;

                        $("#httpTime").text(time + 'ms');
                        $("#httpCode").text(xhr.status + ' ' + xhr.statusText);
                        try{
                            xhr.responseText = JSON.stringify(JSON.parse(xhr.responseText), null, 4);
                            window.ResponseEditor.setOption("mode","application/ld+json");
                        }catch(e){
                            console.log(e);
                        }
                       // window.ResponseEditor.setValue(xhr.responseText);
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

    function resolveRequestHeader() {
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

    $("#chromeExtensionData").on("click",function () {
        console.log("医改办");
        //$("#toolApiContainer").off("click","#sendRequest");
        $("#sendRequest").on("click",function (e) {
            e.stopPropagation();
            var buttonText = $(this).attr("data-loading-text");
            $(this).attr("disabled","disabled").text("");
        });
    });
})(jQuery);