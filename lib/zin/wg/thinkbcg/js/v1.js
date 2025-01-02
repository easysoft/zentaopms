/**
 * 计算分割线的值。
 * Calculates the value of the split line.
 *
 * @param  number splitType
 * @param  array  splitValue
 * @param  array  axisData
 * @access public
 * @return string|number
 */
function calculateMarkLine(splitType, splitValue, axisData)
{
    if(splitType == 0)
    {
        const sum = axisData.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
        return (sum / axisData.length).toFixed(2);
    }
    else
    {
        return splitValue[0] * 100;
    }
}

window.initBcgModel = function()
{
    $.getLib(config.webRoot + 'js/echarts/echarts.common.min.js', {root: false}, function()
    {
        const bcgEcharts         = echarts.init($('.echarts-content>div')[0]);
        const data               = $('.echarts-content').data('blocks');
        const configureDimension = data.runOptions.configureDimension;
        const prerelease         = data.runOptions.prerelease;

        /* 分割线的计算。*/
        /* Calculation of the split line. */
        const markLineX = calculateMarkLine(prerelease.splitType[0], prerelease.splitValue[0], data.basicXAxis);
        const markLineY = calculateMarkLine(prerelease.splitType[1], prerelease.splitValue[1], data.basicYAxis);

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
