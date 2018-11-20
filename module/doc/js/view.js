$(function()
{
    $('#urlIframe').height($('#mainContent').height() - 35);
    $('body').addClass('doc-fullscreen');
    $('#mainContent .fullscreen-btn').click(function()
    {
        $('.side-col').removeClass('hidden');
        $('body').toggleClass('doc-fullscreen');
        if($('body').hasClass('doc-fullscreen')) 
        {
            $('#mainContent .fullscreen-btn').attr('title', retrack);
        }
        else
        {
            $('#mainContent .fullscreen-btn').attr('title', fullscreen);
        }
        setTimeout($.resetToolbarPosition, 50);
    });
})
