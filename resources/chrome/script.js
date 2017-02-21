
var appOptions = {
    filterRequestUrl : "*"
};

function initOptions(callback) {
    chrome.storage.sync.get(null, function(data) {
        $.extend(appOptions, data);
        chrome.storage.sync.set(appOptions);
        callback && callback();
    });
}

// 监听设置项的变化
chrome.storage.onChanged.addListener(function(changes) {
    for (var name in changes) {
        var change = changes[name];
        appOptions[name] = change.newValue;
    }
});



$(function () {
    initOptions(function () {
        $("#siteHost").val(appOptions.filterRequestUrl);
    });
    $("#saveHost").on("click",function () {

        appOptions.filterRequestUrl = $("#siteHost").val();

        chrome.storage.sync.set(appOptions);
        $("#message").text("已保存");

    });

});