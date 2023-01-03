<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/chart.html.php';?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js');?>
<?php js::set('executions', $executions);?>
<div class='header'>
  <div class='img-header'>
    <h2 class='title'>迭代燃尽图大屏</h2>
    <span class='time'><?php echo '更新时间:' . $date;?></span>
  </div>
</div>
<div class='content'>
  <?php if(!empty($executions)):?>
  <div class='burn'>
    <?php foreach($executions as $executionID => $execution):?>
      <div class="container" id="<?php echo 'burn' . $executionID;?>">
      </div>
    <?php endforeach;?>
  </div>
  <?php else:?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->screen->noData;?></span>
    </p>
  </div>
  <?php endif;?>
</div>
<script>
function initBurnChar()
{
    Object.values(executions).forEach(function(execution)
    {
        var chartDom = document.getElementById('burn' + execution.id);
        var myChart = echarts.init(chartDom);
        var option = {
          title: {
            text: execution.name,
            textStyle: {
                color: '#a1c4e9',
                fontSize: 15
            }
          },
          tooltip: {
            trigger: 'axis'
          },
          legend: {
            data: ["<?php echo $lang->execution->charts->burn->graph->actuality;?>","<?php echo $lang->execution->charts->burn->graph->reference;?>"],
            textStyle:{
              color: '#dee0e4',
              fontSize: 14
            },
            right: 0
          },
          color: ['#42526a', '#2567cf', 'red'],
          grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
          },
          toolbox: {
            feature: {
              saveAsImage: {}
            }
          },
          xAxis: {
            type: 'category',
            boundaryGap: false,
            axisLabel: {
              show: true,
              textStyle: {
                color: '#dee0e4'
              }
            },
            data: execution.chartData['labels']
          },
          yAxis: {
            type: 'value',
            axisLabel: {
              show: true,
              textStyle: {
                color: '#dee0e4'
              }
            },
            splitLine: {
              show: true,
              lineStyle:{
                color: ['#182a43'],
                width: 2,
                type: 'solid'
              }
            },
          },
          series: [
            {
              name: "<?php echo $lang->execution->charts->burn->graph->reference;?>",
              symbol: 'circle',
              symbolSize: 4,
              type: 'line',
              areaStyle: {color: '#2667cf00'},
              lineStyle: {
                "width": 2,
                "type": "solid"
              },
              data: JSON.parse(execution.chartData['baseLine'])
            },
            {
              name: "<?php echo $lang->execution->charts->burn->graph->actuality;?>",
              symbol: 'circle',
              symbolSize: 4,
              type: 'line',
              areaStyle: {
                "opacity": 0.8,
                "color": {
                  "colorStops": [
                    {
                      "offset": 0,
                      "color": "rgba(73, 146, 255, 0.5)"
                    },
                    {
                      "offset": 1,
                      "color": "rgba(73, 146, 255, 0.1)"
                    }
                  ],
                  "x": 0,
                  "y": 0,
                  "x2": 0,
                  "y2": 1,
                  "type": "linear",
                  "global": false
                }
              },
              lineStyle: {
                "width": 2,
                "type": "solid"
              },
              data: JSON.parse(execution.chartData['burnLine'])
            },
          ]
        };

        if(execution.chartData['delayLine'])
        {
            var delaySets =
            {
                name: "<?php echo $lang->execution->charts->burn->graph->delay;?>",
                symbol: 'circle',
                symbolSize: 4,
                type: 'line',
                areaStyle: {color: '#2667cf00'},
                lineStyle: {
                  "width": 2,
                  "type": "solid"
                },
                data: JSON.parse(execution.chartData['delayLine'])
            }
            option.title.subtext = '已延期';
            option.title.subtextStyle = {color:"red", fontSize: 15};
            option.series.push(delaySets);
        }

        myChart.setOption(option);
    });
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
