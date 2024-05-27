function changeBrowseDate()
{
    const begin  = $('[name="begin"]').val().replaceAll('-', '');
    const end    = $('[name="end"]').val().replaceAll('-', '');
    const params = condition + '&beginTime=' + begin + '&endTime=' + end;
    loadPage($.createLink('testtask', 'browse', params), '#mainContent');
};

window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'buildName' && row.data.execution != 0 && multipleSprints[row.data.execution] == undefined)
    {
        if(result[0].props) result[0].props['data-app'] = 'project';
    }
    return result;
}
