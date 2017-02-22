chrome.browserAction.onClicked.addListener(function(tab) {
    window.open('https://www.iminho.me/?from=chrome_app');
});

chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {

    var startTime = (new Date()) ;

    $.ajax({
        url : request.url,
        type : request.method,
        data : request.body,
        headers : request.header,
        crossDomain: true,
        xhrFields : { withCredentials : true },
        dataType : "text",
        complete : function (xhr, textStatus) {
            console.log(textStatus);
            var endTime = (new Date()) - startTime;
            var rawHeaders = xhr.getAllResponseHeaders();
            var unpackedHeaders = unpackHeaders(rawHeaders);

            var responseType = "text";

            if(unpackedHeaders.hasOwnProperty("Content-Type")){
                var imageRegExp = new RegExp("image/(.*?)", "gi");

                if(unpackedHeaders["Content-Type"].match(imageRegExp)){
                    responseType = "image";
                }
            }
            var response = {};

            if(responseType === "arraybuffer"){
                response = {
                    time : endTime,
                    timeout: xhr.timeout,
                    responseType: responseType,
                    readyState : xhr.readyState,
                    withCredentials: xhr.withCredentials,
                    rawHeaders: rawHeaders,
                    headers: unpackedHeaders,
                    status: xhr.status,
                    statusText: xhr.statusText,
                    response : getBase64FromArrayBuffer(xhr.response)
                };
            }else{
                response = {
                    time : endTime,
                    timeout: xhr.timeout,
                    responseType: responseType,
                    readyState : xhr.readyState,
                    withCredentials: xhr.withCredentials,
                    rawHeaders: rawHeaders,
                    headers: unpackedHeaders,
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText : xhr.responseText
                };
            }

            chrome.cookies.getAll({url:request.url}, function (cookies) {
                chrome.tabs.query({
                        active: true,
                        currentWindow: true
                    },
                    function (tabs) {
                        response.cookies = cookies;
                        chrome.tabs.sendMessage(tabs[0].id, response);
                    });
            });
        }
    });

});
