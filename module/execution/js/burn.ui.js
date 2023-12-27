window.randTipInfo = function(rowDatas)
{
    let tipHtml = `<p>${rowDatas[0].name} ${workHour} /h</p>`;
    rowDatas.forEach(rowData =>
    {
        if(rowData.data == 'null') return;
        tipHtml += `<div><span style="background: ${rowData.color}; height: 10px; width: 10px; display: inline-block; margin-right: 10px;"></span>${rowData.seriesName} ${rowData.data}</div>`;
    });
    return tipHtml;
}

$(document).on('change', '#burnBy', function()
{
    $.cookie.set('burnBy', $('input[name=burnBy]').val(), {expires:config.cookieLife, path:config.webRoot});

    let interval = typeof($('input[name=interval]').val()) == 'undefined' ? 0 : $('input[name=interval]').val() ;
    loadPage($.createLink('execution', 'burn', 'executionID=' + executionID + '&type=' + type + '&interval=' + interval + '&burnBy=' + $('input[name=burnBy]').val()));
});

$(document).off('change', '#interval').on('change', '#interval', function()
{
    loadPage($.createLink('execution', 'burn', 'executionID=' + executionID + '&type=' + type + '&interval=' + $('input[name=interval]').val()));
});

/* Save burn as image.*/
window.downloadBurn = function()
{
    let $canvas = $('#burnPanel canvas');
    $('#burnPanel').append("<div class='hidden' id='cloneCanvas'></div>");
    $('#cloneCanvas').append($canvas.clone());
    $('#cloneCanvas').append("<img id='cloneImage' />");
    $('#cloneCanvas #cloneImage').attr('src', $canvas.get(0).toDataURL("image/png"));
    setTimeout(function()
    {
        /* Add watermark */
        var canvas = document.querySelector('#cloneCanvas canvas');
        var cans = canvas.getContext('2d');
        canvas.height = canvas.height + 25;
        canvas.width  = canvas.width  + 50;

        // Set background color to white
        cans.fillStyle = '#fff';
        cans.fillRect(0, 0, canvas.width, canvas.height);

        cans.drawImage($('#cloneCanvas #cloneImage').get(0), 0, 25);
        cans.font = "16px Microsoft JhengHei";
        cans.fillStyle = "rgba(17, 17, 17, 0.50)";
        cans.textAlign = 'left';
        cans.textBaseline = 'Middle';
        cans.fillText(watermark, 50, canvas.height - 50);
        cans.fillText(burnYUnit, 0, 20);
        cans.fillText(burnXUnit, canvas.width - 50, canvas.height - 15);

        var type     = 'png';
        var $canvas  = $('#cloneCanvas canvas');
        var imgSrc   = $canvas.get(0).toDataURL("image/png");
        var imgData  = imgSrc.replace(type,'image/octet-stream');
        var filename = executionName + '.' + type;
        saveFile(imgData,filename);
        $('#burnPanel #cloneCanvas').remove();
    }, 500);
}

var saveFile = function(data, filename)
{
    var saveLink = document.createElement('a');
    saveLink.href = data;
    saveLink.download = filename;

    var event = document.createEvent('MouseEvents');
    event.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
    saveLink.dispatchEvent(event);
};
