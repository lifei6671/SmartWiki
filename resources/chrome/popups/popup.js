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