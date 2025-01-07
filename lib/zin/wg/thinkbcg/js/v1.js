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
        const maxX      = Math.max(...data.basicXAxis);
        const minX      = Math.min(...data.basicXAxis);
        const maxY      = Math.max(...data.basicYAxis);
        const minY      = Math.min(...data.basicYAxis);
        const minXAxis  = markLineX <= minX ? markLineX : 10;
        const maxXAxis  = markLineX >= maxX ? markLineX : 10;
        const minYAxis  = markLineY <= minY ? markLineY : 10;
        const maxYAxis  = markLineY >= maxY ? markLineY : 10;

        /* 划分的区域名称的展示。*/
        /* A display of the names of the zones delineated. */
        const graphicData = [];
        for(let i = 0; i < 4; i++)
        {
            let position = (i % 2 == 0) ? { left: '9%' } : { right: '14%' };
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

        const series = [];
        data['series'].forEach((item, index) => {
            series.push({
                name: item[2],
                data: [item],
                type: 'scatter',
                zlevel: index + 1,
                symbolSize: function(itemData) {
                    return itemData[3];
                },
                itemStyle: {
                    opacity: 0.3,
                    color: function(params) {
                        return colors[params.seriesIndex];
                    },
                    emphasis: {
                        opacity: 0.6
                    }
                },
                emphasis: {
                    label: {
                        show: true,
                        position: 'top',
                        distance: 6,
                        fontSize: 20,
                        color: '#39485D',
                        opacity: 1,
                        formatter: function(param) {
                            const dataX = param.data[0].toFixed(2);
                            const dataY = param.data[1].toFixed(2);
                            let text    = `${param.data[2]} (${dataX}, ${dataY})`;
                            if(text.length > 20) text = text.replace(/(.{20})/g, '$1\n');
                            return text;
                        }
                    }
                }
            });
        });
        series.push({
            type: 'scatter',
            markLine: {
                silent: false,
                symbol: 'none',
                label: {
                    show: false
                },
                lineStyle: {
                    width: 2,
                    color: 'rgba(100, 117, 139, 0.3)',
                    type: 'solid'
                },
                emphasis: {
                    lineStyle: {
                        color: 'rgba(100, 117, 139, 0.3)'
                    }
                },
                data: [
                    {xAxis: markLineX},
                    {yAxis: markLineY}
                ]
            }
        });

        const legend = [];
        for(let key in data.products)
        {
            if(key >= 0 && key < colors.length)
            {
                legend.push({
                    name: data.products[key],
                    itemStyle: {
                        color: colors[key]
                    }
                });
            }
        }

        const option = {
            animationDuration: 0,
            grid: {
                left: '3%',
                right: '14%',
                bottom: '5%',
                containLabel: true
            },
            legend: {
                data: legend,
                formatter: function(name) {
                    const reg       = /[\u4E00-\u9FA5]/;
                    const threshold = !reg.test(name) ? 14 : 7;
                    if(name.length > threshold) return name.slice(0, threshold) + '...';
                    return name;
                },
                textStyle: {
                    color: '#4A577F',
                    fontSize: 20
                },
                itemGap: 15,
                right: 10,
                bottom: 'center',
                orient: 'vertical'
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
                min: function(value) {
                    const threshold = minXAxis < -1000 ? 500 : 90;
                    return value.min - minXAxis - threshold;
                },
                max: function(value) {
                    const threshold = maxXAxis > 1000 ? 500 : 90;
                    return value.max + maxXAxis + threshold;
                },
                splitLine: {
                    show: false,
                },
                axisTick: {
                    show: true,
                    inside: true,
                    lineStyle: {
                        width: 2
                    }
                },
                axisLabel: {
                    show: true,
                    fontSize: 16,
                    color: '#64758B',
                    formatter: function(params) {
                        return params.toFixed(2);
                    }
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
                min: function(value) {
                    const threshold = minYAxis < -1000 ? 500 : 90;
                    return value.min - minYAxis - threshold;
                },
                max: function(value) {
                    const threshold = maxYAxis > 1000 ? 500 : 90;
                    return value.max + maxYAxis + threshold;
                },
                splitLine: {
                    show: false
                },
                axisTick: {
                    show: true,
                    inside: true,
                    lineStyle: {
                        width: 2
                    }
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
                    show: true,
                    fontSize: 16,
                    color: '#64758B',
                    formatter: function(params) {
                        return params.toFixed(2);
                    }
                }
            },
            series: series
        };
        bcgEcharts.setOption(option);
    });
}
