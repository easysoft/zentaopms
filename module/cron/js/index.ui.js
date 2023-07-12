$(document).on('click', '.ajaxRefresh', function()
{
    $.get($(this).attr('href'), function(){loadPage($.createLink('cron', 'index'));})
})
