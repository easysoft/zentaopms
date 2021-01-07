$(function()
{
    $('#mainMenu input[name^="PRJMine"]').click(function()
    {
        var PRJMine = $(this).is(':checked') ? 1 : 0;
        $.cookie('PRJMine', PRJMine, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
});

