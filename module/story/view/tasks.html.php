<?php
include '../../common/view/header.lite.html.php';
include '../../common/view/chart.html.php';
?>
<div id='titlebar'>
  <div class='heading'>
    <span><?php echo html::icon($lang->icons['report']);?></span>
    <small class='text-muted'> <?php echo $lang->story->tasks;?></small>
  </div>
</div>
<div class='tasksList'>
  <form class='form-condensed' target='hiddenwin'>
    <table class='table table-fixed'>
      <thead>
        <tr class='text-center'>
          <th class='w-40px'>    <?php echo $lang->idAB;?></th>
          <th class='w-p30'>   <?php echo $lang->task->name;?></th>
          <th class='w-pri'>   <?php echo $lang->priAB;?></th>
          <th class='w-status'><?php echo $lang->statusAB;?></th>
          <th class='w-user'>  <?php echo $lang->task->assignedToAB;?></th>
          <th class='w-40px'>  <?php echo $lang->task->estimateAB;?></th>
          <th class='w-40px'>  <?php echo $lang->task->consumedAB;?></th>
          <th class='w-40px'>  <?php echo $lang->task->leftAB;?></th>
          <th class='w-40px'>  <?php echo $lang->task->progess;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tasks as $key => $task):?>
        <tr class='text-center'>
          <td><?php echo $task->id;?></td>
          <td class='text-left' title="<?php echo $task->name?>"><?php echo $task->name;?></td>
          <td><span class='<?php echo 'pri' . zget($lang->task->priList, $task->pri, $task->pri)?>'><?php echo $task->pri == '0' ? '' : zget($lang->task->priList, $task->pri, $task->pri);?></span></td>
          <td><?php echo $lang->task->statusList[$task->status];?></td>
          <td><?php echo zget($users, $task->assignedTo, $task->assignedTo);?></td>
          <td><?php echo $task->estimate;?></td>
          <td><?php echo $task->consumed;?></td>
          <td><?php echo $task->left;?></td>
          <td><div class='progress-pie' title="<?php echo $task->progess?>%" data-value='<?php echo $task->progess;?>'></div></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
