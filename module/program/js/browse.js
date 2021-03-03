$(function()
{
    $('input[name^="showClosed"]').click(function()
    {
        var showClosed = $(this).is(':checked') ? 1 : 0;
        $.cookie('showClosed', showClosed, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
});
