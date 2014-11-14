<?php
/**
 * The task view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: task.html.php 5101 2013-07-12 00:44:27Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<script language="Javascript">var type='<?php echo $type;?>';</script>
<div id='featurebar'>
  <ul class='nav'>
    <?php
    echo "<li id='assignedToTab'>" . html::a(inlink('task', "type=assignedTo"),  $lang->my->taskMenu->assignedToMe) . "</li>";
    echo "<li id='openedByTab'>"   . html::a(inlink('task', "type=openedBy"),    $lang->my->taskMenu->openedByMe)   . "</li>";
    echo "<li id='finishedByTab'>" . html::a(inlink('task', "type=finishedBy"),  $lang->my->taskMenu->finishedByMe) . "</li>";
    echo "<li id='closedByTab'>"   . html::a(inlink('task', "type=closedBy"),    $lang->my->taskMenu->closedByMe)   . "</li>";
    echo "<li id='canceledByTab'>" . html::a(inlink('task', "type=canceledBy"),  $lang->my->taskMenu->canceledByMe) . "</li>";
    ?>
  </ul>
</div>
<form method='post' id='myTaskForm'>
  <table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='tasktable'>
    <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
    <thead>
    <tr class='text-center'>
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
    <tr class='text-center'>
      <td class='text-left'>
        <?php if($canBatchEdit or $canBatchClose):?><input type='checkbox' name='taskIDList[]' value='<?php echo $task->id;?>' /><?php endif;?>
        <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));?>
      </td>
      <td><span class='<?php echo 'pri' . zget($lang->task->priList, $task->pri, $task->pri);?>'><?php echo zget($lang->task->priList, $task->pri, $task->pri);?></span></td>
      <td class='nobr text-left'><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->projectID"), $task->projectName);?></td>
      <td class='text-left nobr'><?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name);?></td>
      <td><?php echo $task->estimate;?></td>
      <td><?php echo $task->consumed;?></td>
      <td><?php echo $task->left;?></td>
      <td class='<?php if(isset($task->delay)) echo 'delayed';?>'><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
      <td class='task-<?php echo $task->status;?>'><?php echo $lang->task->statusList[$task->status];?></td>
      <td><?php echo $users[$task->openedBy];?></td>
      <td class='text-right'>
        <?php 
        common::printIcon('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $task, 'list', 'hand-right', '', 'iframe', true);
        common::printIcon('task', 'start',    "taskID=$task->id", $task, 'list', 'play', '', 'iframe', true);
        common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);
        common::printIcon('task', 'finish',   "taskID=$task->id", $task, 'list', 'ok-sign', '', 'iframe', true);
        common::printIcon('task', 'close',    "taskID=$task->id", $task, 'list', 'off', '', 'iframe', true);
        common::printIcon('task', 'edit', "taskID=$task->id", '', 'list', 'pencil');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='11'>
        <?php if(count($tasks)):?>
        <div class='table-actions clearfix'>
        <?php 
        if($canBatchEdit or $canBatchClose) echo "<div class='btn-group'>" . html::selectButton() . '</div>';
        echo "<div class='btn-group'>";
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('task', 'batchEdit', "projectID=0&orderBy=$orderBy");
            echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\"");
        }
        if($canBatchClose)
        {
            $actionLink = $this->createLink('task', 'batchClose');
            echo html::commonButton($lang->close, "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
        }
        echo '</div>';
        ?>
        </div> 
        <?php endif;?>
        <?php $pager->show();?>
        </td>
      </tr>
    </tfoot>
  </table> 
</form>
<?php js::set('listName', 'tasktable')?>
<?php include '../../common/view/footer.html.php';?>
