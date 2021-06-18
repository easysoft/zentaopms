<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/chart.html.php';?>
<?php if(isset($config->maxVersion) or isset($config->proVersion) or isset($config->bizVersion)):?>
<style>#mainContent > .side-col.col-lg{width: 235px}</style>
<style>.hide-sidebar #sidebar{width: 0 !important}</style>
<?php endif;?>
<?php $chartData = array('labels' => array(), 'data' => array());?>
<div id='mainContent' class='main-row'>
  <div class='side-col col-lg' id='sidebar'>
    <?php include 'blockreportlist.html.php';?>
  </div>
  <div class='main-col'>
    <div class='cell'>
      <div class="row" id='conditions'>
        <div class='w-220px col-md-3 col-sm-6'>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->report->execution . $lang->report->begin;?></span>
            <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $begin, "class='form-control form-date' onchange='changeDate(this.value, \"$end\")'");?></div>
          </div>
        </div>
        <div class='w-220px col-md-3 col-sm-6'>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->report->execution . $lang->report->end;?></span>
            <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $end, "class='form-control form-date' onchange='changeDate(\"$begin\", this.value)'");?></div>
          </div>
        </div>
      </div>
    </div>
    <?php if(empty($executions)):?>
    <div class="cell">
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
      </div>
    </div>
    <?php else:?>
    <div class='cell'>
      <div class='panel'>
        <div class="panel-heading">
          <div class="panel-title"><?php echo $title;?></div>
          <nav class="panel-actions btn-toolbar"></nav>
        </div>
        <div data-ride='table'>
          <table class='table table-condensed table-striped table-bordered table-fixed no-margin' id='executionList'>
            <thead>
              <tr class='colhead'>
                <th class='w-id'><?php echo $lang->report->id;?></th>
                <th><?php echo $lang->report->execution;?></th>
                <th class="w-100px"><?php echo $lang->report->estimate;?></th>
                <th class="w-100px"><?php echo $lang->report->consumed;?></th>
                <th class="w-100px"><?php echo $lang->report->deviation;?></th>
                <th class="w-100px"><?php echo $lang->report->deviationRate;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($executions as $id  =>$execution):?>
              <tr class="text-center">
                <td><?php echo $id;?></td>
                <td class="text-left" title="<?php echo $execution->name;?>"><?php echo html::a($this->createLink('execution', 'view', "executionID=$id"), $execution->name);?></td>
                <td><?php echo $execution->estimate;?></td>
                <td><?php echo $execution->consumed;?></td>
                <?php $deviation = $execution->consumed - $execution->estimate;?>
                <td class="deviation">
                <?php
                    if($deviation > 0)
                    {
                        echo '<span class="up">&uarr;</span>' . $deviation;
                    }
                    else if($deviation < 0)
                    {
                        echo '<span class="down">&darr;</span>' . abs($deviation);
                    }
                    else
                    {
                        echo '<span class="zero">0</span>';
                    }
                ?>
                </td>
                <td class="deviation">
                  <?php
                  $num = $execution->estimate ? round($deviation / $execution->estimate * 100, 2) : 'n/a';
                  if($num >= 50)
                  {
                      echo '<span class="u50">' . $num . '%</span>';
                  }
                  elseif($num >= 30)
                  {
                      echo '<span class="u30">' . $num . '%</span>';
                  }
                  elseif($num >= 10)
                  {
                      echo '<span class="u10">' . $num . '%</span>';
                  }
                  elseif($num > 0)
                  {
                      echo '<span class="u0">' . abs($num) . '%</span>';
                  }
                  elseif($num <= -20)
                  {
                      echo '<span class="d20">' . abs($num) . '%</span>';
                  }
                  elseif($num < 0)
                  {
                      echo '<span class="d0">' . abs($num) . '%</span>';
                  }
                  elseif($num == 'n/a')
                  {
                      echo '<span class="zero">' . $num . '</span>';
                  }
                  else
                  {
                      echo '<span class="zero">' . abs($num) . '%</span>';
                  }

                  $chartData['labels'][] = $execution->name;
                  $chartData['data'][]   = $deviation;
                  ?>
                </td>
              </tr>
            <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php if(!empty($chartData)):?>
    <div class="cell">
      <?php
      if(count($chartData['labels']) > 30)
      {
      $chartData['labels'] = array_slice($chartData['labels'], 0, 30);
      $chartData['data']   = array_slice($chartData['data'],   0, 30);
      }
      ?>
      <div class='panel'>
        <div class='panel-heading'>
          <div class='panel-title'><?php echo $lang->report->deviationChart?></div>
        </div>
        <div class='panel-body'>
          <canvas id='deviationChart' width='800' height='300' data-bezier-curve='false' data-responsive='true'></canvas>
        </div>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<script>
function initChart()
{
    var data =
    {
        labels: <?php echo json_encode($chartData['labels'])?>,
        datasets: [
        {
            label: "",
            color: "#0033CC",
            pointStrokeColor: '#0033CC',
            pointHighlightStroke: '0033CC',
            data: <?php echo json_encode($chartData['data'])?>
        }]
    };

    var burnChart = $("#deviationChart").lineChart(data,
    {
        animation: !($.zui.browser && $.zui.browser.ie === 8),
        pointDotStrokeWidth: 0,
        pointDotRadius: 1,
        datasetFill: false,
        datasetStroke: true,
        scaleShowBeyondLine: false,
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>h"

    });
}
</script>
<?php include '../../common/view/footer.html.php';?>
