<?php $_GET['onlybody'] = 'no';?>
<table class='table-1 fixed colored tablesorter datatable' id='taskList'>
  <?php $vars = "projectID=$project->id&status=$status&parma=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage"; ?>
  <thead>
  <tr class='colhead'>
    <th class='w-id'>    <?php common::printOrderLink('id',           $orderBy, $vars, $lang->idAB);?></th>
    <th class='w-pri'>   <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
    <th class='w-p30'>   <?php common::printOrderLink('name',         $orderBy, $vars, $lang->task->name);?></th>
    <th class='w-status'><?php common::printOrderLink('status',       $orderBy, $vars, $lang->statusAB);?></th>
    <th class='w-70px'>  <?php common::printOrderLink('deadline',     $orderBy, $vars, $lang->task->deadlineAB);?></th>

    <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
    <th class='w-id'>    <?php common::printOrderLink('openedDate',   $orderBy, $vars, $lang->task->openedDateAB);?></th>
    <?php endif;?>

    <th class='w-user'>  <?php common::printOrderLink('assignedTo',   $orderBy, $vars, $lang->task->assignedToAB);?></th>
    <th class='w-user'>  <?php common::printOrderLink('finishedBy',   $orderBy, $vars, $lang->task->finishedByAB);?></th>

    <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
    <th class='w-40px'>  <?php common::printOrderLink('finishedDate', $orderBy, $vars, $lang->task->finishedDateAB);?></th>
    <?php endif;?>

    <th class='w-35px'>  <?php common::printOrderLink('estimate',     $orderBy, $vars, $lang->task->estimateAB);?></th>
    <th class='w-40px'>  <?php common::printOrderLink('consumed',     $orderBy, $vars, $lang->task->consumedAB);?></th>
    <th class='w-40px'>  <?php common::printOrderLink('left',         $orderBy, $vars, $lang->task->leftAB);?></th>
    <?php if($project->type == 'sprint') print '<th>' and common::printOrderLink('story', $orderBy, $vars, $lang->task->story) and print '</th>';?>
    <th class='w-140px {sorter:false}'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($tasks as $task):?>
  <?php $class = $task->assignedTo == $app->user->account ? 'style=color:red' : ''; ?>
  <tr class='a-center'>
    <td>
      <input type='checkbox' name='taskIDList[]'  value='<?php echo $task->id;?>'/> 
      <?php if(!common::printLink('task', 'view', "task=$task->id", sprintf('%03d', $task->id))) printf('%03d', $task->id);?>
    </td>
    <td><span class='<?php echo 'pri'. $lang->task->priList[$task->pri]?>'><?php echo $lang->task->priList[$task->pri];?></span></td>
    <td class='a-left' title="<?php echo $task->name?>">
      <?php 
      if(!common::printLink('task', 'view', "task=$task->id", $task->name)) echo $task->name;
      if($task->fromBug) echo html::a($this->createLink('bug', 'view', "id=$task->fromBug"), "[BUG#$task->fromBug]", '_blank', "class='bug'");
      ?>
    </td>
    <td class=<?php echo $task->status;?> >
      <?php
      $storyChanged = ($task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion);
      $storyChanged ? print("<span class='warning'>{$lang->story->changed}</span> ") : print($lang->task->statusList[$task->status]);
      ?>
    </td>
    <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo substr($task->deadline, 5, 6);?></td>

    <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
    <td><?php echo substr($task->openedDate, 5, 6);?></th>
    <?php endif;?>

    <td <?php echo $class;?>><?php echo $task->assignedTo == 'closed' ? 'Closed' : $task->assignedToRealName;?></td>
    <td><?php echo $users[$task->finishedBy];?></td>

    <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
    <td><?php echo substr($task->finishedDate, 5, 6);?></th>
    <?php endif;?>

    <td><?php echo $task->estimate;?></td>
    <td><?php echo $task->consumed;?></td>
    <td><?php echo $task->left;?></td>
    <?php
    if($project->type == 'sprint')
    {
        echo '<td class="a-left" title="' . $task->storyTitle . '"';
        if($task->storyID)
        {
          if(!common::printLink('story', 'view', "storyid=$task->storyID", $task->storyTitle)) print $task->storyTitle;
        }
        echo '</td>';
    }
    ?>
    <td class='a-right'>
      <?php
      common::printIcon('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $task, 'list', '', '', 'iframe', true);
      common::printIcon('task', 'start',    "taskID=$task->id", $task, 'list', '', '', 'iframe', true);

      common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
      if($browseType == 'needconfirm')
      {
          $lang->task->confirmStoryChange = $lang->confirm;
          common::printIcon('task', 'confirmStoryChange', "taskid=$task->id", '', 'list', '', 'hiddenwin');
      }
      common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
      common::printIcon('task', 'close',    "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
      common::printIcon('task', 'edit',"taskID=$task->id", '', 'list');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <?php $columns = ($this->cookie->windowWidth > $this->config->wideSize ? 14 : 12) - ($project->type == 'sprint' ? 0 : 1);?>
      <td colspan='<?php echo $columns;?>'>
        <div class='f-left'>
        <?php 
        if(count($tasks))
        {
            echo html::selectAll() . html::selectReverse();

            if(common::hasPriv('task', 'batchEdit'))
            {
                $actionLink = $this->createLink('task', 'batchEdit', "projectID=$projectID&from=projectTask&orderBy=$orderBy");
                echo html::commonButton($lang->edit, "onclick=\"changeAction('projectTaskForm', 'batchEdit', '$actionLink')\"");
            }
            if(common::hasPriv('task', 'batchClose') and strtolower($browseType) != 'closedBy')
            {
                $actionLink = $this->createLink('task', 'batchClose');
                echo html::commonButton($lang->close, "onclick=\"changeAction('projectTaskForm', 'batchClose', '$actionLink')\"");
            }
        }
        echo $summary;
        ?>
        </div>
        <?php $pager->show();?>
      </td>
    </tr>
  </tfoot>
</table>
