<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/chart.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['report-file']);?></span>
    <strong> <?php echo $title;?></strong>
  </div>
</div>
<div class='side'>
  <?php include 'blockreportlist.html.php';?>
  <div class='panel panel-body' style='padding: 10px 6px'>
    <div class='text proversion'>
      <strong class='text-danger small text-latin'>PRO</strong> &nbsp;<span class='text-important'><?php echo $lang->report->proVersion;?></span>
    </div>
  </div>
</div>
<div class='main'>
  <div class='input-group w-400px input-group-sm'>
    <span class='input-group-addon'><?php echo $lang->projectCommon . $lang->report->beginAndEnd;?></span>
    <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $begin, "class='w-100px form-control form-date' onchange='changeDate(this.value, \"$end\")'");?></div>
    <span class='input-group-addon'><?php echo $lang->report->to;?></span>
    <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $end, "class='form-control form-date' onchange='changeDate(\"$begin\", this.value)'");?></div>
  </div>
  <table class='table table-condensed table-striped table-bordered tablesorter table-fixed active-disabled' id='projectList'>
    <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->report->id;?></th>
          <th><?php echo $lang->report->project;?></th>
          <th class="w-100px"><?php echo $lang->report->estimate;?></th>
          <th class="w-100px"><?php echo $lang->report->consumed;?></th>
          <th class="w-100px"><?php echo $lang->report->deviation;?></th>
          <th class="w-100px"><?php echo $lang->report->deviationRate;?></th>
        </tr>
    </thead>
    <tbody>
    <?php $chartData = array();?>
    <?php foreach($projects as $id  =>$project):?>
      <tr class="text-center">
        <td><?php echo $id;?></td>
        <td class="text-left"><?php echo html::a($this->createLink('project', 'view', "projectID=$id"), $project->name);?></td>
        <td><?php echo $project->estimate;?></td>
        <td><?php echo $project->consumed;?></td>
        <?php $deviation = $project->consumed - $project->estimate;?>
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
          $num = $project->estimate ? round($deviation / $project->estimate * 100, 2) : 'n/a';
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

          $chartData['labels'][] = $project->name;
          $chartData['data'][]   = $deviation;
          ?>
        </td>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table> 
  <?php if($chartData):?>
  <?php
  if(count($chartData['labels']) > 30)
  {
  $chartData['labels'] = array_slice($chartData['labels'], 0, 30);
  $chartData['data']   = array_slice($chartData['data'],   0, 30);
  }
  ?>
  <div class='panel'>
    <div class='panel-heading'><strong><?php echo $lang->report->deviationChart?></strong></div>
    <div class='panel-body canvas-wrapper'><div class='chart-canvas'><canvas id='deviationChart' width='800' height='300' data-bezier-curve='false' data-responsive='true'></canvas></div></div>
  </div>
  <?php endif;?>
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
