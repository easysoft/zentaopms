<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <?php include 'blockreportlist.html.php';?>
      <div class='proversion'><?php echo $lang->report->proVersion;?></div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable border-sep'>
        <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->report->id;?></th>
          <th class="w-200px"><?php echo $lang->report->project;?></th>
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
        <?php foreach($projects as $id  =>$project):?>
          <tr class="a-center">
            <td><?php echo $id;?></td>
            <td align="left"><?php echo $project->name;?></td>
            <td><?php echo $project->stories;?></td>
            <td><?php echo $project->bugs;?></td>
            <td><?php echo $project->devConsumed;?></td>
            <td><?php echo $project->testConsumed;?></td>
            <td><?php echo round($project->devConsumed / (($project->testConsumed < 1) ? 1 : $project->testConsumed), 1);?></td>
            <td><?php echo $project->estimate;?></td>
            <td><?php echo $project->consumed;?></td>
            <?php $deviation = $project->consumed - $project->estimate;?>
            <td><?php echo $deviation > 0 ? ('+' . $deviation) : ($deviation);?></td>
            <td>
              <?php 
                if($project->estimate)
                {
                    echo $deviation > 0 ? ('+' . round($deviation / $project->estimate * 100, 2)) : round($deviation / $project->estimate * 100, 2) . '%';
                }
                else
                {
                    echo '0%';
                }
              ?>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table> 
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
