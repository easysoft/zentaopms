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
                axisTick: {
                    show: true,
                    inside: true
                },
                axisLabel: {
                    show: true,
                    formatter: function(params) {
                        return ('' + params).slice(0, 4)
                    },
                },
                axisLine: {
                    show: true,
                    onZero: false,
                    symbol: ['none', 'arrow'],
                    lineStyle: {
                        width: 2,
                        color: 'rgba(100, 117, 139, 0.7)',
                    }
                }
            },
            yAxis: {
                name: '%',
                nameLocation: configureDimension.yAxisOrder == 1 ? 'start' : 'end',
                nameTextStyle: {
                    fontSize: 20,
                    color: '#000'
                },
                inverse: configureDimension.yAxisOrder == 1,
                type: 'value',
                splitLine: {
                    show: false
                },
                axisTick: {
                    show: true,
                    inside: true
                },
                axisLine: {
                    show: true,
                    onZero: false,
                    symbol: ['none', 'arrow'],
                    lineStyle: {
                        width: 2,
                        color: 'rgba(100, 117, 139, 0.7)',
                    }
                },
                axisLabel: {
                    show: true
                }
            },
        };
        bcgEcharts.setOption(option);
    });
}
