$(document).on('click', '.ajaxRefresh', function()
{
    $.get($(this).attr('href'), function(){loadPage($.createLink('cron', 'index'));})
})

window.confirmTurnon = function()
{
    if(window.confirm(confirmTurnonMessage)) return $.get($.createLink('cron', 'turnon', 'confirm=yes'), function(){loadPage($.createLink('cron', 'index'));})
    return false;
}
