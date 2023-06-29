$('.nav.nav-tabs a[data-toggle="tab"]').on('shown.zui.tab', function()
{
    var tabID = $(this).attr('href').replaceAll('#', '');
    $('#' + tabID + ' .chart > div').data('zui.ECharts').chart.resize();
});
