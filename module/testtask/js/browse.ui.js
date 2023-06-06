$(document).on('change', '#begin,#end', function()
{
    const begin  = $('#begin').val().replaceAll('-', '');
    const end    = $('#end').val().replaceAll('-', '');
    const params = condition + '&beginTime=' + begin + '&endTime=' + end;
    loadPage($.createLink('testtask', 'browse', params), '#mainContent');
});
