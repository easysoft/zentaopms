<?php include '../../common/view/m.header.html.php';?>
</div>
<div data-role="content">
  <h3 class="title"><?php echo "TASK #$task->id $task->name"?></h3>
  <div class="textContent"><?php echo $task->desc?></div>
  <?php include '../../common/view/m.action.html.php';?>
</div>
<div data-role='footer' data-position='fixed'>
  <div data-role='navbar'>
    <ul>
      <?php 
      common::printIcon('task', 'assignTo',       "projectID=$task->project&taskID=$task->id", $task);
      common::printIcon('task', 'start',          "taskID=$task->id", $task);
      common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task);
      common::printIcon('task', 'finish',         "taskID=$task->id", $task);
      common::printIcon('task', 'close',          "taskID=$task->id", $task);
      common::printIcon('task', 'activate',       "taskID=$task->id", $task);
      ?>
    </ul>
  </div>
</div>
<?php include '../../common/view/m.footer.html.php';?>
