function syncComments()
{
    $.get(link, function(data)
    {
        if(data == 'finish') return loadPage(browseLink);
        if(isNaN(Number(data))) return zui.Modal.alert(data);

        var count = parseInt(data);
        if(isNaN(count)) count = 0;
        $('#commits').html(parseInt($('#commits').html()) + count);

        setTimeout(syncComments, 100);
    });
}

setTimeout(syncComments, 500);
