$(document).on('click', '.ajaxRefresh', function()
{
    $.get($(this).attr('href'), function(){loadPage($.createLink('cron', 'index'));})
})

$(document).on('click', '.ajaxDelete', function(e)
{
    if(window.confirm(confirmDelete)) return $.get($.createLink('cron', 'delete', 'id=' + $(this).data('id') + '&confirm=yes'), function(){loadPage($.createLink('cron', 'index'));})
    e.stopPropagation();
    return false;
})

window.confirmTurnon = function()
{
    if(window.confirm(confirmTurnonMessage)) return $.get($.createLink('cron', 'turnon', 'confirm=yes'), function(){loadPage($.createLink('cron', 'index'));})
    return false;
}
