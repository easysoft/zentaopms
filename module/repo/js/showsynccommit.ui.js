function syncComments()
{
    $.get(link, function(data)
    {
        if(data == 'finish') return openUrl(browseLink);

        var count = parseInt(data);
        if(isNaN(count)) count = 0;
        $('#commits').html(parseInt($('#commits').html()) + count);

        setTimeout(syncComments, 100);
    });
}

setTimeout(syncComments, 500);