var appOptions = {
    filterRequestUrl : "*",
    isLoadScript : false
};

/**
 * 解析响应头
 * @param data
 * @returns {*}
 */
function unpackHeaders(data) {
    if (data === null || data === "") {
        return [];
    }
    var vars = [], hash;
    var hashes = data.split('\n');
    var header;

    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i];
        if (!hash) {
            continue;
        }

        var loc = hash.search(':');

        if (loc !== -1) {
            var name = hash.substr(0, loc).trim(),
                value = hash.substr(loc + 1).trim();

            var item = {} ;
            item[name] = value;

            vars.push(item);
        }
    }

    return vars;
}
/**
 * 编码二进制格式数据
 * @param responseData
 * @returns {string}
 */
function getBase64FromArrayBuffer(responseData) {
    var uInt8Array = new Uint8Array(responseData);
    var i = uInt8Array.length;
    var binaryString = new Array(i);
    while (i--)
    {
        binaryString[i] = String.fromCharCode(uInt8Array[i]);
    }
    var data = binaryString.join('');

    var base64 = window.btoa(data);

    return base64;
}