<div class='block linkbox2'>
<table class='table-1 fixed colored'>
  <caption>
    <div class='f-left'><span class='icon-doing'></span><?php echo $lang->my->task;?></div>
    <div class='f-right'><?php echo html::a($this->createLink('my', 'task'), $lang->more . "<span class='icon-more'></span>");?></div>
  </caption>
  <?php 
  foreach($tasks as $task)
  {
      echo "<tr><td class='nobr'>" . "#$task->id " . html::a($this->createLink('task', 'view', "id=$task->id"), $task->name) . "</td><td width='5'</td></tr>";
  }
  ?>
</table>
</div>
