$(function()
{
    $('#urlIframe').height($('#mainContent').height() - 35);
    $('#mainMenu .fullscreen-btn').click(function()
    {
        $('body').toggleClass('doc-fullscreen');
        setTimeout($.resetToolbarPosition, 250);
    });
})
