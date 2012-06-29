<?php include '../../common/view/header.html.php';?>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->report->list;?></div>
      <div class='box-content'>
      <ul id="report-list">
        <li><?php echo html::a(inlink('projectDeviation'), $lang->report->projectDeviation);?></li>
      </ul>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable border-sep'>
        <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->report->id;?></th>
          <th><?php echo $lang->report->project;?></th>
          <th><?php echo $lang->report->stories;?></th>
          <th><?php echo $lang->report->bugs;?></th>
          <th><?php echo $lang->report->devConsumed;?></th>
          <th><?php echo $lang->report->testConsumed;?></th>
          <th><?php echo $lang->report->devTestRate;?></th>
          <th><?php echo $lang->report->estimate;?></th>
          <th><?php echo $lang->report->consumed;?></th>
          <th><?php echo $lang->report->deviation;?></th>
          <th><?php echo $lang->report->deviationRate;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($projects as $project):?>
          <tr class="a-center">
            <td><?php echo $project->id;?></td>
            <td><?php echo $project->name;?></td>
            <td><?php echo $project->stories;?></td>
            <td><?php echo $project->bugs;?></td>
            <td><?php echo $project->devConsumed;?></td>
            <td><?php echo $project->testConsumed;?></td>
            <td><?php echo $project->devConsumed . ' : ' . $project->testConsumed;?></td>
            <td><?php echo $project->estimate;?></td>
            <td><?php echo $project->consumed;?></td>
            <td><?php echo ($project->consumed - $project->estimate) > 0 ? '+' . ($project->consumed - $project->estimate) : ($project->consumed - $project->estimate);?></td>
            <td>
              <?php echo (($project->consumed - $project->estimate) > 0 ? '+' : '') . round(abs($project->consumed - $project->estimate) / $project->consumed * 100, 2) . '%';?>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table> 
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
