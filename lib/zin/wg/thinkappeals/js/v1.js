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

/**
 * 初始化 $APPEALS 图表数据后的回调函数。
 * The callback function after initializing the $APPEALS chart data.
 *
 * @access public
 * @return void
 */
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
