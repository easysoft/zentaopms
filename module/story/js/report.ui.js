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

window.clickInit = function(e)
{
    initReport();
};

$(document).off('click', 'a[data-toggle=tab].active').on('click', 'a[data-toggle=tab]', function()
{
    initReport();
});

function initReport()
{
    const chartType = $('a[data-toggle=tab].active').data('param');
    const form      = new FormData();
    $('input[name=charts]').each(function()
    {
        if($(this).prop('checked')) form.append('charts[]', $(this).val());
    })
    postAndLoadPage($.createLink('story', 'report', params + '&chartType=' + chartType + '&projectID=' + projectID), form, '#report');
}




