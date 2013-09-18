<div class='block linkbox2'>
<table class='table-1 fixed colored'>
  <caption>
    <div class='f-left'><i class='icon icon-tasks'></i>&nbsp; <?php echo $lang->my->task;?></div>
    <div class='f-right'><?php echo html::a($this->createLink('my', 'task'), $lang->more . "&nbsp;<i class='icon-th icon icon-double-angle-right'></i>");?></div>
  </caption>
  <?php 
  foreach($tasks as $task)
  {
      echo "<tr><td class='nobr'>" . "#$task->id " . html::a($this->createLink('task', 'view', "id=$task->id"), $task->name, '', "title=$task->name") . "</td><td width='5'</td></tr>";
  }
  ?>
</table>
</div>
