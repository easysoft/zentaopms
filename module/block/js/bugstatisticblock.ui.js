$('.bugstatistic-block .nav.nav-tabs').on('show', function(event, info)
{
    $(info[1] + ' .chart').each(function()
    {
        $(this).find('div').data('zui.ECharts').chart.resize();
    });
});
