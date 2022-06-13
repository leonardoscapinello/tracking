function insertParam(key, value) {
    key = fixedEncodeURIComponent(key);
    value = fixedEncodeURIComponent(value);
    const kvp = document.location.search.substring(1).split('&');
    if ("" === kvp) {
        document.location.search = '?' + key + '=' + value;
    } else {
        let i = kvp.length;
        let x;
        while (i--) {
            x = kvp[i].split('=');

            if (x[0] === key) {
                x[1] = value;
                kvp[i] = x.join('=');
                break;
            }
        }
        if (i < 0) {
            kvp[kvp.length] = [key, value].join('=');
        }
        document.location.search = kvp.join('&');
    }
}

function fixedEncodeURIComponent(str) {
    return encodeURIComponent(str).replace(/[!'()*]/g, function (c) {
        return '%' + c.charCodeAt(0).toString(16);
    });
}