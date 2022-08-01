$(function()
{
    $('input[name^="showAutoCase"]').click(function()
    {
        var showAutoCase = $(this).is(':checked') ? 1 : 0;
        $.cookie('showAutoCase', showAutoCase, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
})
