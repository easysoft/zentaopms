function changeBrowseDate()
{
    const begin  = $('[name="begin"]').val().replaceAll('-', '');
    const end    = $('[name="end"]').val().replaceAll('-', '');
    const params = condition + '&beginTime=' + begin + '&endTime=' + end;
    loadPage($.createLink('testtask', 'browse', params), '#mainContent');
};
