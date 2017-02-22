(function ($) {
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

    //设置发送按钮样式
    function button(state) {

        var $this = $("#sendRequest");
        if (state == "loading") {
            var buttonText = $this.text();
            var buttonLoadText = $this.attr("data-loading-text");
            $this.attr("disabled", "disabled").text(buttonLoadText).attr("data-text", buttonText);
        } else if (state == "reset") {
            var buttonText = $this.attr("data-text");
            $this.removeAttr("disabled").text(buttonText);
        }
    }

    $("#chromeExtensionEventTriggerBtn").on("click", function () {

        $("#sendRequest").on("click", function (e) {
            e.stopPropagation();
            button("loading");
            var $then = $("#toolApiContainer");

            var request = {
                header : resolveRequestHeader(),
                body : resolveRequestBody(),
                url : $then.find("#requestUrl").val(),
                method : $then.find("input[name='http_method']").val()
            };

            chrome.runtime.sendMessage(request,function () {
                console.log("消息已发送")
            });

            chrome.runtime.onMessage.addListener(function returnResult(request, sender, sendResponse) {
                console.log(request)
                $("#chromeExtensionResponse").text(JSON.stringify(request));
                $("#chromeExtensionResponseEventTriggerBtn").trigger("click");

                chrome.extension.onMessage.removeListener(returnResult);

            });

            button("reset");
        });
    });
})(jQuery);