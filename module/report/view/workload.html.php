<?php include '../../common/view/header.html.php';?>
<style>
.rowcolor{background:#F9F9F9;}
</style>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <?php include 'blockreportlist.html.php';?>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id="workload">
        <thead>
        <tr class='colhead'>
          <th><?php echo $lang->report->user;?></th>
          <th><?php echo $lang->report->project;?></th>
          <th><?php echo $lang->report->task;?></th>
          <th><?php echo $lang->report->remain;?></th>
          <th><?php echo $lang->report->taskTotal;?></th>
          <th><?php echo $lang->report->manhourTotal;?></th>
        </tr>
        </thead>
        <tbody>
        <?php $color = false;?>
        <?php foreach($workload as $account => $load):?>
          <?php if(!array_key_exists($account, $users)) continue;?>
          <tr class="a-center">
            <td rowspan="<?php echo count($load['task']);?>"><?php echo $users[$account];?></td>
            <?php $id = 1;?>
            <?php foreach($load['task'] as $project => $info):?>
            <?php $class = $color ? 'rowcolor' : '';?>
            <?php if($id != 1) echo '<tr class="a-center">';?>
            <td class="<?php echo $class;?>"><?php echo $project;?></td>
            <td class="<?php echo $class;?>"><?php echo $info['count'];?></td>
            <td class="<?php echo $class;?>"><?php echo $info['manhour'];?></td>
            <?php if($id == 1):?>
            <td rowspan="<?php echo count($load['task']);?>">
                <?php echo $load['total']['count'];?>
            </td>
            <td rowspan="<?php echo count($load['task']);?>">
                <?php echo $load['total']['manhour'];?>
            </td>
            <?php endif;?>
            <?php if($id != 1) echo '</tr>'; $id ++;?>
            <?php $color = !$color;?>
            <?php endforeach;?>
          </tr>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table> 
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
