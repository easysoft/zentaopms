$(document).on('click', '.ajaxRefresh', function()
{
    $.get($(this).data('url'));
    loadPage($.createLink('cron', 'index'));
    return;
})
