$(function()
{
    $('#PRJMine1').click(function()
    {
        var PRJMine = $(this).is(':checked') ? 1 : 0;
        $.cookie('PRJMine', PRJMine, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
});

$('#project' + programID).addClass('active');
$(".tree .active").parent('li').addClass('active');
