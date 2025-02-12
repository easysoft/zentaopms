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
 * 格式化图例名称。
 * Format the legend name.
 *
 * @param  string name
 * @access public
 * @return string
 */
window.formatLegend = function(name)
{
    const parts     = name.split('/');
    const reg       = /[\u4E00-\u9FA5]/; // 检查字符串中是否包含汉字
    const threshold = !reg.test(name) ? 60 : 30;
    if(parts.length > 0) name = `{bolder|${parts[0]}}/${parts.slice(1).join(' ')}`;

    return name.length > threshold ? name.substr(0, threshold) + '...' : name;
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
    const chartData = $('.appleals-chart div').data('zui.ECharts');
    const dataURL   = chartData.chart.getDataURL({pixelRatio: 2});
    const img       = new Image();
    img.onload = function()
    {
        $('.appleals-chart-content').append(img);
        $('.appleals-chart-content img').css({marginTop: '-45px'});
        $('.appleals-chart').addClass('hidden');
    };
    img.src = dataURL;
}
