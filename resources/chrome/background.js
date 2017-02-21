// 嵌入脚本
chrome.tabs.executeScript(null, { file: "lib/jquery-1.12.4.min.js" }, function() {
    chrome.tabs.executeScript(null, { file: "script.js" });
});

initOptions();