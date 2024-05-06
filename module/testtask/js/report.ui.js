window.selectAll = function(e)
{
    let allChecked = true;
    $('input[name=charts]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('input[name=charts]').each(function()
    {
        $(this).prop('checked', !allChecked);
    });
};

window.clickInit = function()
{
    const chartType = $('a[data-toggle=tab].active').data('param');

    initReport(chartType);
};

window.changeTab = function(e)
{
    const chartType = $(e.target).closest('.font-medium').data('param');

    initReport(chartType);
}

window.initReport = function(chartType)
{
    const form = new FormData();
    $('input[name=charts]').each(function()
    {
        if($(this).prop('checked')) form.append('charts[]', $(this).val());
    })
    postAndLoadPage($.createLink('testtask', 'report', params + '&chartType=' + chartType), form, '#report,pageJS/.zin-page-js,#configJS');
}
