$(function()
{
    if($('#urlIframe').size() > 0)
    {
        var defaultHeight = $.cookie('windowHeight') - $('#header').height() - $('#footer').height() - $('#mainMenu').height() - 50;
        $('#urlIframe').height(defaultHeight);
        setTimeout($.resetToolbarPosition, 50);
    }
    $('body').addClass('doc-fullscreen');
    $('#mainContent .fullscreen-btn').click(function()
    {
        $('.side-col').toggleClass('hidden');
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
