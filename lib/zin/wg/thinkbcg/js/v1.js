window.initBcgModel = function()
{
    $.getLib(config.webRoot + 'js/echarts/echarts.common.min.js', {root: false}, function()
    {
        const bcgEcharts         = echarts.init($('.echarts-content>div')[0]);
        const data               = $('.echarts-content').data('blocks');
        const configureDimension = data.runOptions.configureDimension;

        const option = {
            xAxis: {
                name: '%',
                nameLocation: configureDimension.xAxisOrder == 1 ? 'start' : 'end',
                nameTextStyle: {
                    fontSize: 20,
                    color: '#000'
                },
                inverse: configureDimension.xAxisOrder == 1,
                type: 'value',
                splitLine: {
                    show: false,
                },
            },
        };
        bcgEcharts.setOption(option);
    });
}
