<table class='table-1 tablesorter fixed colored' id='tasktable'>
  <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-id'>    <?php common::printOrderLink('id',          $orderBy, $vars, $lang->idAB);?></th>
    <th class='w-pri'>   <?php common::printOrderLink('pri',         $orderBy, $vars, $lang->priAB);?></th>
    <th class='w-150px'> <?php common::printOrderLink('project',     $orderBy, $vars, $lang->task->project);?></th>
    <th>                 <?php common::printOrderLink('name',        $orderBy, $vars, $lang->task->name);?></th>
    <th class='w-hour'>  <?php common::printOrderLink('estimate',    $orderBy, $vars, $lang->task->estimateAB);?></th>
    <th class='w-hour'>  <?php common::printOrderLink('consumed',    $orderBy, $vars, $lang->task->consumedAB);?></th>
    <th class='w-hour'>  <?php common::printOrderLink('left',        $orderBy, $vars, $lang->task->leftAB);?></th>
    <th class='w-date'>  <?php common::printOrderLink('deadline',    $orderBy, $vars, $lang->task->deadlineAB);?></th>
    <th class='w-status'><?php common::printOrderLink('status',      $orderBy, $vars, $lang->statusAB);?></th>
    <th class='w-user'>  <?php common::printOrderLink('openedBy',    $orderBy, $vars, $lang->openedByAB);?></th>
    <th class='w-140px'> <?php echo $lang->actions;?></th>
  </tr>
  </thead>   
  <tbody>
  <?php $canBatchEdit  = common::hasPriv('task', 'batchEdit');?>
  <?php $canBatchClose = (common::hasPriv('task', 'batchClose') and $type != 'closedBy');?>
  <?php foreach($tasks as $task):?>
  <tr class='a-center'>
    <td class='a-left'>
      <?php if($canBatchEdit or $canBatchClose):?><input type='checkbox' name='taskIDList[]' value='<?php echo $task->id;?>' /><?php endif;?>
      <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?>
    </td>
    <td><span class='<?php echo 'pri' . $lang->task->priList[$task->pri];?>'><?php echo isset($lang->task->priList[$task->pri]) ? $lang->task->priList[$task->pri] : $task->pri;?></span></td>
    <td class='nobr a-left'><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->projectID"), $task->projectName);?></th>
    <td class='a-left nobr'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name);?></td>
    <td><?php echo $task->estimate;?></td>
    <td><?php echo $task->consumed;?></td>
    <td><?php echo $task->left;?></td>
    <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
    <td class='<?php echo $task->status;?>'><?php echo $lang->task->statusList[$task->status];?></td>
    <td><?php echo $users[$task->openedBy];?></td>
    <td class='a-right'>
      <?php 
      common::printIcon('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $task, 'list', '', '', 'iframe', true);
      common::printIcon('task', 'start',    "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
      common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
      common::printIcon('task', 'finish',   "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
      common::printIcon('task', 'close',    "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
      common::printIcon('task', 'edit', "taskID=$task->id", '', 'list');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='11'>
      <?php if(count($tasks)):?>
      <div class='f-left'>
      <?php 
      if($canBatchEdit or $canBatchClose) echo html::selectAll() . html::selectReverse();
      if($canBatchEdit)
      {
          $actionLink = $this->createLink('task', 'batchEdit', "projectID=0&orderBy=$orderBy");
          echo html::commonButton($lang->edit, "onclick=\"changeAction('myTaskForm', 'batchEdit', '$actionLink')\"");
      }
      if($canBatchClose)
      {
          $actionLink = $this->createLink('task', 'batchClose');
          echo html::commonButton($lang->close, "onclick=\"changeAction('myTaskForm', 'batchClose', '$actionLink')\"");
      }
       ?>
      </div> 
      <?php endif;?>
      <?php $pager->show();?>
      </td>
    </tr>
  </tfoot>
</table> 
