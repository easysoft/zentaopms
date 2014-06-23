<div class='panel panel-block'>
  <div class='panel-heading'>
    <i class='icon icon-tasks'></i> <strong><?php echo $lang->my->task;?></strong>
    <div class='panel-actions pull-right'>
      <?php echo html::a($this->createLink('my', 'task'), $lang->more . " <i class='icon icon-double-angle-right'></i>");?>
    </div>
  </div>
  <table class='table table-condensed table-hover table-striped table-borderless table-fixed'>
  <?php 
  foreach($tasks as $task)
  {
      echo "<tr><td class='nobr'>" . "#$task->id " . html::a($this->createLink('task', 'view', "id=$task->id"), $task->name, '', "title=$task->name") . "</td><td width='5'></td></tr>";
  }
  ?>
  </table>
</div>
