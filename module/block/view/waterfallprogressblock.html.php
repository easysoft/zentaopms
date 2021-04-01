<div class="panel-body">
  <?php if(!isset($charts['labels']) || empty($charts['labels'])): ?>
  <div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
  <?php else:?>
  <style>
  .block-waterfallprogress .milestone-chart {box-shadow: none}
  .block-waterfallprogress .milestone-chart.has-scrollbar {margin-bottom: -10px}
  </style>
  <div class='milestone-chart' id='milestoneChart'>
    <div class='chart-canvas scrollbar-hover'>
      <div class='chart-wrapper'>
        <canvas width='400' height='120' data-responsive='true'></canvas>
      </div>
    </div>
    <div class='chart-unit'><?php echo "({$lang->execution->workHour})";?></div>
    <div class='chart-legend'>
      <span class="barline line-pv">PV</span>
      <span class="barline line-ev">EV</span>
      <span class="barline line-ac">AC</span>
    </div>
  </div>
  <style>
  .milestone-chart {position: relative}
  .milestone-chart .chart-canvas {overflow: auto; max-width: 1002px}
  .milestone-chart .chart-wrapper {background: none; padding: 15px 0 0 0}
  .milestone-chart .chart-unit {position: absolute; left: 10px; top: 0;}
  .milestone-chart .chart-legend {position: absolute; right: 5px; top: 0;}
  .milestone-chart .barline {padding-left: 20px; position: relative; display: inline-block;  line-height: 20px}
  .milestone-chart .barline + .barline {margin-left: 5px}
  .milestone-chart .barline:before {content: ' '; display: block; position: absolute; top:  8px; left: 0; width: 16px; height: 3px; background: #0c64eb}
  .milestone-chart .barline.line-ev:before {background: rgb(0, 218, 136)}
  .milestone-chart .barline.line-ac:before {background: rgb(255, 145, 0)}
  </style>
  <script>
  function initMilestoneChart()
  {
      var data =
      {
          labels: <?php echo isset($charts['labels']) ? json_encode($charts['labels']) : '[]'?>,
          datasets: [
          {
              label: 'PV',
              color: '#0c64eb',
              pointColor: '#0c64eb',
              pointStrokeColor: '#0c64eb',
              pointHighlightStroke: '#0c64eb',
              fillColor: 'rgba(0,106,241, .07)',
              pointHighlightFill: '#fff',
              data: <?php echo $charts['PV']?>
          },
          {
              label: 'EV',
              color: 'rgb(0, 218, 136)',
              pointColor: 'rgb(0, 218, 136)',
              pointStrokeColor: 'rgb(0, 218, 136)',
              pointHighlightStroke: 'rgb(0, 218, 136)',
              fillColor: 'rgb(0, 218, 136, .07)',
              pointHighlightFill: '#fff',
              data: <?php echo $charts['EV']?>
          },
          {
              label: 'AC',
              color: 'rgb(255, 145, 0)',
              pointColor: 'rgb(255, 145, 0)',
              pointStrokeColor: 'rgb(255, 145, 0)',
              pointHighlightStroke: 'rgb(255, 145, 0)',
              fillColor: 'rgb(255, 145, 0, .07)',
              pointHighlightFill: '#fff',
              data: <?php echo $charts['AC']?>
          }]
      };

      var betterWidth = data.labels.length ? data.labels.length * Math.min(100, Math.max(20, (config.clientLang.startsWith('zh') ? 11 : 9) * data.labels[data.labels.length - 1].length)) : 20;
      var renderChart = function()
      {
          var $chart       = $('#milestoneChart');
          var $wrapper     = $chart.find('.chart-wrapper');
          var $canvas      = $chart.find('canvas');
          var $chartCanvas = $chart.find('.chart-canvas');
          if (betterWidth > 400)
          {
              var updateWrapperSize = function()
              {
                  $wrapper.hide();
                  $chartCanvas.css('max-width', 'initial');
                  var maxWidth = $chartCanvas.width();
                  $chartCanvas.css('max-width', maxWidth);
                  $chart.toggleClass('has-scrollbar', maxWidth < betterWidth);
                  $wrapper.show();
              };
              updateWrapperSize();
              $(window).on('resize', updateWrapperSize);

              $wrapper.css('min-width', betterWidth);
              $canvas.attr(
              {
                  width:  betterWidth,
                  height: Math.min(200, Math.floor(betterWidth / 4))
              });
          }
          $canvas.lineChart(data,
          {
              animation: betterWidth < 400,
              pointDotStrokeWidth: 1,
              pointDotRadius: 2,
              datasetStrokeWidth: 2,
              datasetFill: false,
              datasetStroke: true,
              scaleShowBeyondLine: true,
              responsive: true,
              bezierCurve: false,
              scaleFontColor: '#838A9D',
              tooltipXPadding: 10,
              tooltipYPadding: 10,
              multiTooltipTitleTemplate: '<%= label %> <?php echo $lang->execution->workHour;?> /h',
              multiTooltipTemplate: '<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>',
          });
      }

      setTimeout(renderChart, betterWidth > 200 ? 100 : 10);
  }
  initMilestoneChart();
  </script>
  <?php endif; ?>
</div>
