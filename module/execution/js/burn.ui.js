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
