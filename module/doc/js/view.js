$(function()
{
    $('#urlIframe').height($('#mainContent').height() - 35);
    $('body').addClass('doc-fullscreen');
    $('#mainContent .fullscreen-btn').attr('title', reset);
    setTimeout($.resetToolbarPosition, 50);
    $('#mainContent .fullscreen-btn').click(function()
    {
        $('body').toggleClass('doc-fullscreen');
        if($('body').hasClass('doc-fullscreen')) 
        {
            $('#mainContent .fullscreen-btn').attr('title', reset);
        }
        else
        {
            $('#mainContent .fullscreen-btn').attr('title', fullscreen);
        }
        setTimeout($.resetToolbarPosition, 50);
    });
})
