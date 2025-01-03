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

/**
 * 初始波士顿模型。
 * Initial Boston Model.
 *
 * @access public
 * @return void
 */
window.initBcgModel = function()
{
    $.getLib(config.webRoot + 'js/echarts/echarts.common.min.js', {root: false}, function()
    {
        const bcgEcharts         = echarts.init($('.echarts-content>div')[0]);
        const colors             = ['#2294FB', '#22C98D', '#8166EE', '#FF8058', '#FF9F46', '#FFCD6C', '#04D2CC', '#2B67E5', '#E08BE7', '#B2B9C5'];
        const data               = $('.echarts-content').data('blocks');
        const configureDimension = data.runOptions.configureDimension;
        const prerelease         = data.runOptions.prerelease;

        /* 分割线的计算。*/
        /* Calculation of the split line. */
        const markLineX = calculateMarkLine(prerelease.splitType[0], prerelease.splitValue[0], data.basicXAxis);
        const markLineY = calculateMarkLine(prerelease.splitType[1], prerelease.splitValue[1], data.basicYAxis);

        /* 划分的区域名称的展示。*/
        /* A display of the names of the zones delineated. */
        const graphicData = [];
        for(let i = 0; i < 4; i++)
        {
            let position = (i % 2 == 0) ? { left: '7%' } : { right: '14%' };
            position[(i < 2) ? 'top' : 'bottom'] = (i < 2) ? '4%' : '10%';

            graphicData.push({
                type: 'text',
                style: {
                    text: prerelease.blocks[i] || '',
                    width: 160,
                    overflow: 'truncate',
                    fontSize: 20,
                    fontWeight: 600,
                    fill: '#64758B',
                },
                ...position,
                onmouseover: function() {
                    this.setStyle({overflow: 'break'});
                },
                onmouseout: function() {
                    this.setStyle({overflow: 'truncate'});
                }
            });
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

        const legend = [];
        for(let key in data.products)
        {
            legend.push({
                name: data.products[key],
                itemStyle: {
                    color: colors[key - 1]
                }
            });
        }

        const option = {
            grid: {
                left: '3%',
                right: '12%',
                bottom: '5%',
                containLabel: true
            },
            graphic: graphicData,
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
