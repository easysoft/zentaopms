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
    const chartData = $('.appleals-chart div').data('zui.ECharts');
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

/**
 * 格式化维度标题。
 * Format dimension name.
 *
 * @param  string value
 * @access public
 * @return string
 */
window.formatIndicatorName = function(value)
{
    const count =  /^[A-Za-z\s]+$/.test(value) ? 16 : 5;
    let list    = value.split('');
    let result  = '';
    for(let i = 1; i <= list.length; i++)
    {
        if((i % count) === 0 && list[i] !== undefined)
        {
            result += list[i - 1] + '\n';
        }
        else
        {
            result += list[i - 1];
        }
    }
    return result;
}
