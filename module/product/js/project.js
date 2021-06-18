$(function()
{
    $('#mainMenu input[name^="involved"]').click(function()
    {
        var involved = $(this).is(':checked') ? 1 : 0;
        $.cookie('involved', involved, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
});

