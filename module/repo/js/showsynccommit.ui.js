function syncComments()
{
    $.get(link, function(data)
    {
        if(data == 'finish') return openUrl(browseLink);

        $('#commits').html(parseInt($('#commits').html()) + parseInt(data));

        setTimeout(syncComments, 100);
    });
}
setTimeout(syncComments, 500);