<div class="panel panel-block">
  <div class="panel-heading">
    <i class='icon icon-list-ul'></i> <strong><?php echo $lang->my->todo;?></strong>
    <div class="panel-actions pull-right">
      <?php echo html::a($this->createLink('my', 'todo'), $lang->more . " <i class='icon icon-double-angle-right'></i>");?>
    </div>
  </div>
  <table class='table table-condensed table-hover table-striped table-borderless table-fixed'>
  <?php 
  foreach($todos as $todo)
  {
      echo "<tr><td class='nobr'>" . "#$todo->id " . html::a($this->createLink('todo', 'view', "id=$todo->id"), $todo->name, '', "title=$todo->name") . "</td><td width='5'></td></tr>";
  }
  ?>
  </table>
</div>
