$('.nav.nav-tabs').on('show', function(event, info)
{
    const tabID = info[1].replaceAll('#', '');
    $('#' + tabID + ' canvas').each(function()
    {
        $(this).parent().parent().data('zui.ECharts').chart.resize();
    });
});
