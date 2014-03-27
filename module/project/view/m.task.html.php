<?php include '../../common/view/m.header.html.php';?>
</div>
<?php $this->session->set('taskType', '');?>
<?php foreach($tasks as $task):?>
<div  data-role="collapsible-set">
  <div data-role="collapsible" data-collapsed="<?php echo $this->session->taskID == $task->id ? 'false' : 'true'?>" class='collapsible'>
    <?php if($this->session->taskID == $task->id) echo "<script>showDetail('task', $task->id);</script>";?>
    <h1 onClick="showDetail('task', <?php echo $task->id;?>)"><?php echo $task->name;?></h1>
    <div><?php echo $task->desc?></div>
    <div id='item<?php echo $task->id;?>'></div>
    <div data-role='content' class='text-center'>
      <?php 
      common::printIcon('task', 'assignTo',       "projectID=$task->project&taskID=$task->id", $task);
      common::printIcon('task', 'start',          "taskID=$task->id", $task);
      common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task);
      common::printIcon('task', 'finish',         "taskID=$task->id", $task);
      common::printIcon('task', 'close',          "taskID=$task->id", $task);
      common::printIcon('task', 'activate',       "taskID=$task->id", $task);
      ?>
    </div>
    <?php if(end($tasks) == $task) echo "<hr />";?>
  </div>
</div>
<?php endforeach;?>
<?php $pager->show('left', 'mobile')?>
<?php include '../../common/view/m.footer.html.php';?>
