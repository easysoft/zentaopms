$(function()
{
    $('[data-toggle=tooltip]').tooltip();

    if(chartData)
    {
        var i = 0;
        var series     = [];
        var colors     = ['#33B4DB', '#7ECF69', '#FFC73A', '#FF5A61', '#50C8D0', '#AF5AFF', '#4EA3FF', '#FF8C5A', '#6C73FF'];
        //var background = ['#E1F4FA', '#ECF8E9', '#FFF7E2', '#FFE7E8', '#E5F7F8', '#F3E7FF', '#E5F1FF', '#FFEEE7', '#E9EAFF'];

        $.each(chartData['line'], function(label, set)
        {
            series.push({
              name: label,
              type: 'line',
              stack: 'Total',
              color: colors[i],
              areaStyle: {
                color: colors[i],
                opacity: 0.2
              },
              emphasis: {
                focus: 'series'
              },
              data: eval(set)
            })
            i ++;
        })

        var chartDom = document.getElementById('cfdChart');
        var CFD      = echarts.init(chartDom);
        var option;

        option = {
          tooltip: {
            trigger: 'axis',
            axisPointer: {
              type: 'cross',
              label: {
                backgroundColor: '#6a7985'
              }
            }
          },
          legend: {
            data: Object.keys(chartData['line'])
          },
          grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
          },
          xAxis: [
          {
              type: 'category',
              boundaryGap: false,
              data: chartData['labels'],
              axisLine:
              {
                  show: true,
                  lineStyle:
                  {
                      color: '#999',
                      width:1
                  }
              }
          }],
          yAxis: [
          {
              type: 'value',
              minInterval: 1,
              axisLine:
              {
                  show: true,
                  lineStyle:
                  {
                      color: ['#999'],
                      width: 1
                  }
              }
            }
          ],
          series: series,
        };

        option && CFD.setOption(option);
        window.addEventListener('resize', CFD.resize);
    }

    $('#type').change(function()
    {
        location.href = createLink('execution', 'cfd', 'executionID=' + executionID + '&type=' + $(this).val());
    });
});
