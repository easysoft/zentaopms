window.handleTabChange = function(event)
{
    $(event.target).find('[z-use-echarts]').each(function()
    {
        const echart = $(this).zui();
        if(echart) echart.chart.resize();
    });
};
