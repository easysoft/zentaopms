<?php
/**
 * The task group view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: grouptask.html.php 4143 2013-01-18 07:01:06Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treetable.html.php';?>
<?php include './taskheader.html.php';?>
<table class='table table-fixed' id='treetable'>
  <thead>
    <tr>
      <th class='w-120px'></th>
      <th><?php echo $lang->task->name;?></th>
      <th class='w-pri'> <?php echo $lang->priAB;?></th>
      <th class='w-user'><?php echo $lang->task->assignedTo;?></th>
      <th class='w-user'><?php echo $lang->task->finishedBy;?></th>
      <th class='w-50px'><?php echo $lang->task->estimateAB;?></th>
      <th class='w-50px'><?php echo $lang->task->consumedAB;?></th>
      <th class='w-50px'><?php echo $lang->task->leftAB;?></th>
      <th class='w-50px'><?php echo $lang->typeAB;?></th>
      <th class='w-80px'><?php echo $lang->task->deadlineAB;?></th>
      <th class='w-80px'><?php echo $lang->task->status;?></th>
      <th class='w-60px'><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <?php  
    $taskSum       = 0;
    $statusWait    = 0;
    $statusDone    = 0;
    $statusDoing   = 0;
    $statusClosed  = 0;  
    $totalEstimate = 0.0;
    $totalConsumed = 0.0;
    $totalLeft     = 0.0;
  ?>
  <?php $i = 0;?>
  <?php foreach($tasks as $groupKey => $groupTasks):?>
  <?php $groupClass = ($i % 2 == 0) ? 'even' : 'highlight-warning'; $i ++;?>
  <tr id='node-<?php echo $groupKey;?>' class='actie-disabled group-title'>
    <td class='<?php echo $groupClass;?> text-left large strong group-name'><?php echo $groupKey;?></td>
    <td colspan='11'><?php if($groupByList) echo $groupByList[$groupKey];?></td>
  </tr>
  <?php
    $groupWait     = 0;
    $groupDone     = 0;
    $groupDoing    = 0;
    $groupClosed   = 0;  
    $groupEstimate = 0.0;
    $groupConsumed = 0.0;
    $groupLeft     = 0.0;
  ?>
  <?php foreach($groupTasks as $task):?>
  <?php $assignedToClass = $task->assignedTo == $app->user->account ? 'style=color:red' : '';?>
  <?php $taskLink        = $this->createLink('task','view',"taskID=$task->id"); ?>
  <?php  
    $totalEstimate  += $task->estimate;
    $totalConsumed  += $task->consumed;
    $totalLeft      += ($task->status == 'cancel' ? 0 : $task->left);

    $groupEstimate  += $task->estimate;
    $groupConsumed  += $task->consumed;
    $groupLeft      += ($task->status == 'cancel' ? 0 : $task->left);

    if($task->status == 'wait')
    {
        $statusWait++;
        $groupWait++;
    }
    elseif($task->status == 'doing')
    {
        $statusDoing++;
        $groupDoing++;
    }
    elseif($task->status == 'done')
    {
        $statusDone++;
        $groupDone++;
    }
    elseif($task->status == 'closed')
    {
        $statusClosed++;
        $groupClosed++;
    }
    $groupSum = count($groupTasks);
    $taskSum += count($tasks);
   ?>
    <tr id='<?php echo $task->id;?>' class='a-center child-of-node-<?php echo $groupKey;?>'>
      <td class='<?php echo $groupClass;?>'></td>
      <td class='text-left'>&nbsp;<?php echo $task->id . $lang->colon; if(!common::printLink('task', 'view', "task=$task->id", $task->name)) echo $task->name;?></td>
      <td><span class='<?php echo 'pri' . zget($lang->task->priList, $task->pri, $task->pri)?>'><?php echo zget($lang->task->priList, $task->pri, $task->pri);?></span></td>
      <td <?php echo $assignedToClass;?>><?php echo $task->assignedToRealName;?></td>
      <td><?php echo $users[$task->finishedBy];?></td>
      <td><?php echo $task->estimate;?></td>
      <td><?php echo $task->consumed;?></td>
      <td><?php echo $task->left;?></td>
      <td><?php echo $lang->task->typeList[$task->type];?></td>
      <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
      <td class=<?php echo $task->status;?> ><?php echo $lang->task->statusList[$task->status];?></td>
      <td>
        <?php common::printIcon('task', 'edit', "taskid=$task->id", '', 'list');?>
        <?php common::printIcon('task', 'delete', "projectID=$task->project&taskid=$task->id", '', 'list', '', 'hiddenwin');?>
      </td>
    </tr>
    <?php endforeach;?>
    <tr class='child-of-node-<?php echo $groupKey;?> <?php echo $groupClass;?>'>
      <td colspan='12' class='a-right groupdivider'>
        <div class='text'>
        <?php if($groupBy == 'assignedto' and isset($members[$task->assignedTo])) printf($lang->project->memberHours, $users[$task->assignedTo], $members[$task->assignedTo]->totalHours);?>
        <?php printf($lang->project->groupSummary, $groupSum, $groupWait, $groupDoing, $groupEstimate, $groupConsumed, $groupLeft);?></div>
      </td>
    </tr>
  <?php endforeach;?>
</table>
<script language='Javascript'>$('#<?php echo $browseType;?>Tab').addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
