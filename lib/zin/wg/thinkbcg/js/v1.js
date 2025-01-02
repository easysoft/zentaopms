window.initBcgModel = function()
{
    $.getLib(config.webRoot + 'js/echarts/echarts.common.min.js', {root: false}, function()
    {
        const bcgEcharts         = echarts.init($('.echarts-content>div')[0]);
        const data               = $('.echarts-content').data('blocks');
        const configureDimension = data.runOptions.configureDimension;
        const prerelease         = data.runOptions.prerelease;

        /* 分割线的展示。*/
        /* Display of dividing lines. */
        let markLineX = 0;
        let markLineY = 0;
        if(prerelease.splitType[0] == 0)
        {
            const sum = data.basicXAxis.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
            markLineX = (sum / data.basicXAxis.length).toFixed(2);
        }
        else
        {
            markLineX = prerelease.splitValue[0][0] * 100;
        }
        if(prerelease.splitType[1] == 0)
        {
            const sum = data.basicYAxis.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
            markLineY = (sum / data.basicYAxis.length).toFixed(2);
        }
        else
        {
            markLineY = prerelease.splitValue[1][0] * 100;
        }

        const series = [{
            type: 'scatter',
            markLine: {
                silent: false,
                symbol: 'none',
                label: {
                    show: false
                },
                lineStyle: {
                    width: 2,
                    color: '#EBEDF3',
                    type: 'solid'
                },
                emphasis: {
                    lineStyle: {
                        color: '#EBEDF3'
                    }
                },
                data: [
                    {xAxis: markLineX},
                    {yAxis: markLineY}
                ]
            }
        }];

        const option = {
            grid: {
                left: '3%',
                right: '12%',
                bottom: '5%',
                containLabel: true
            },
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
            series: series
        };
        bcgEcharts.setOption(option);
    });
}
