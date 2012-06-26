<?php include '../../common/view/header.html.php';?>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->report->list;?></div>
      <div class='box-content'>
        <?php echo html::a(inlink('workload'), $lang->report->workload);?>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable border-sep'>
        <thead>
        <tr class='colhead'>
          <th><?php echo $lang->report->user;?></th>
          <th colspan="4"><?php echo $lang->report->task;?></th>
          <th colspan="3"><?php echo $lang->report->bug;?></th>
        </tr>
        </thead>
        <tbody>
          <tr class="a-center">
            <td></td>
            <td><?php echo $lang->report->project;?></td>
            <td><?php echo $lang->report->task;?></td>
            <td><?php echo $lang->report->remain;?></td>
            <td><?php echo $lang->report->total;?></td>
            <td><?php echo $lang->report->product;?></td>
            <td><?php echo $lang->report->bug;?></td>
            <td><?php echo $lang->report->total;?></td>
          </tr>
        <?php foreach($workload as $user => $load):?>
          <tr class="a-center">
            <td rowspan="<?php echo count($load['task']);?>"><?php echo $user;?></td>
            <td colspan="4">
              <table>
              <?php foreach($load['task'] as $project => $task)?>
              <tr>
              <td><?php echo $task['count']?></td>
              <td><?php echo $task['manhour']?></td>
              <td rowspan="<?php echo count($load['task']);?>"></td>
              </tr>
              </table>
            </td>
            <td colspan="3">
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table> 
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
