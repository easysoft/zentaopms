$(function()
{
    $('input[name^="mine"]').click(function()
    {
        var mine = $(this).is(':checked') ? 1 : 0;
        $.cookie('mine', mine, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
});
