<div class='block linkbox2'>
<table class='table-1 fixed colored'>
  <caption>
    <div class='f-left'><span class='icon-todo'></span><?php echo $lang->my->todo;?></div>
    <div class='f-right'><?php echo html::a($this->createLink('my', 'todo'), $lang->more . "<span class='icon-more'></span>");?></div>
  </caption>
  <?php 
  foreach($todos as $todo)
  {
      echo "<tr><td class='nobr'>" . "#$todo->id " . html::a($this->createLink('todo', 'view', "id=$todo->id"), $todo->name) . "</td><td width='5'</td></tr>";
  }
  ?>
</table>
</div>
