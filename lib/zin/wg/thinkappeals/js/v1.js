/**
 * 格式化坐标标签数据。
 * Format coordinate label data.
 *
 * @param  object params
 * @access public
 * @return number
 */
window.formatSeriesLabel = function(params)
{
    return params?.value?.toFixed(2);
}

window.initedAppealsChart = function()
{
    const chartData = $('.appleals-chart').find('div').data('zui.ECharts');
    const dataURL   = chartData.chart.getDataURL({pixelRatio: 2});
    const img       = new Image();
    img.onload = function()
    {
        $('.appleals-chart-content').append(img);
        $('.appleals-chart-content img').css({marginTop: '-60px'});
        $('.appleals-chart').addClass('hidden');
    };
    img.src = dataURL;
}
