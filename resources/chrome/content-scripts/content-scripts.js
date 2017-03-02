(function ($) {
    var options = {};
    /**
     * 解析请求的 ContentType
     * @param header
     * @returns {string}
     */
    function getRequestContentType(header) {
        var contentType = 'application/x-www-form-urlencoded';
        if (header) {
            if (header.hasOwnProperty('contentType')) {
                contentType = header.contentType;
            }
            if (header.hasOwnProperty('ContentType')) {
                contentType = header.ContentType;
            }
        }
        return contentType;
    }

    /**
     * 解析请求头信息
     * @returns {{}}
     */
    function resolveRequestHeader() {
        var header = {};
        $("#headers>table>tbody>tr").each(function (index, domEle) {
            var checkbox = $(domEle).find('input[type="checkbox"]').is(":checked");
            if (checkbox) {
                var key = $(domEle).find("input[name='key']").val();
                if (key && key !== "") {
                    header[key] = $(domEle).find("input[name='value']").val();
                }
            }
        });

        return header;
    }

    /**
     * 解析请求的内容
     * @returns {{}}
     */
    function resolveRequestBody() {
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
            if(window.RawEditor != null){
                body = $("#rawModeData").val();
            }else{
                body = $("#rawModeData").val();
            }
        }

        return body;
    }

    //设置发送按钮样式
    function button(state) {

        var $this = $("#sendRequest");
        console.log(state)
        if (state == "loading") {
            $this.attr("disabled", "disabled");
        } else if (state == "reset") {
            $this.removeAttr("disabled");
        }
    }

    /**
     * 用来绑定按钮点击后发送请求事件
     */
    function bindSendEvent() {
        $("#sendRequest").on("click", function (e) {
            e.stopPropagation();

            var $then = $("#toolApiContainer");

            var request = {
                header : resolveRequestHeader(),
                body : resolveRequestBody(),
                url : $then.find("#requestUrl").val(),
                method : $then.find("input[name='http_method']").val()
            };
            if(request.url === ""){
                alert("请输入请求的URL.");
                return false;
            }
            console.log(request);

            button("loading");
            chrome.runtime.sendMessage(request,function () {
                //console.log("消息已发送")
            });

            chrome.runtime.onMessage.addListener(function returnResult(request, sender, sendResponse) {
               // console.log(request)
                $("#chromeExtensionResponse").text(JSON.stringify(request));
                $("#chromeExtensionResponseEventTriggerBtn").trigger("click");

                chrome.extension.onMessage.removeListener(returnResult);
                button("reset");
            });


        });
    }

    /**
     * 当页面变更后，重写绑定事件。
     */
    $("#chromeExtensionEventTriggerBtn").on("click",bindSendEvent);

    bindSendEvent();
})(jQuery);