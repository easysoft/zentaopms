<?php
/**
 * The task view file of project module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/table2csv.html.php';?>
<?php include './taskheader.html.php';?>
<div class='yui-d0'>
  <table class='table-1 fixed colored tablesorter datatable'>
    <?php $vars = "projectID=$project->id&status=all&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage"; ?>
    <thead>
    <tr class='colhead'>
      <th class='w-id'>    <?php common::printOrderLink('id',       $orderBy, $vars, $lang->idAB);?></th>
      <th class='w-pri'>   <?php common::printOrderLink('pri',      $orderBy, $vars, $lang->priAB);?></th>
      <th class='w-p30'>   <?php common::printOrderLink('name',     $orderBy, $vars, $lang->task->name);?></th>
      <th class='w-user'>  <?php common::printOrderLink('owner',    $orderBy, $vars, $lang->task->owner);?></th>
      <th class='w-hour'>  <?php common::printOrderLink('estimate', $orderBy, $vars, $lang->task->estimateAB);?></th>
      <th class='w-hour'>  <?php common::printOrderLink('consumed', $orderBy, $vars, $lang->task->consumedAB);?></th>
      <th class='w-hour'>  <?php common::printOrderLink('left',     $orderBy, $vars, $lang->task->leftAB);?></th>
      <th class='w-date'>  <?php common::printOrderLink('deadline', $orderBy, $vars, $lang->task->deadlineAB);?></th>
      <th class='w-status'><?php common::printOrderLink('status',   $orderBy, $vars, $lang->statusAB);?></th>
      <th><?php common::printOrderLink('story', $orderBy, $vars, $lang->task->story);?></th>
      <th class='w-70px {sorter:false}'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($tasks as $task):?>
    <?php $class = $task->owner == $app->user->account ? 'style=color:red' : '';?>
    <tr class='a-center'>
      <td><?php if(!common::printLink('task', 'view', "task=$task->id", sprintf('%03d', $task->id))) printf('%03d', $task->id);?></td>
      <td><?php echo $lang->task->priList[$task->pri];?></td>
      <td class='a-left nobr'><?php if(!common::printLink('task', 'view', "task=$task->id", $task->name)) echo $task->name;?></td>
      <td <?php echo $class;?>><?php echo $task->ownerRealName;?></td>
      <td><?php echo $task->estimate;?></td>
      <td><?php echo $task->consumed;?></td>
      <td><?php echo $task->left;?></td>
      <td class=<?php if(isset($task->delay)) echo 'delayed';?>><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
      <td class=<?php echo $task->status;?> >
        <?php
        if($task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion)
        {
            echo "<span class='warning'>{$lang->story->changed}</span> ";
        }
        else
        {
            echo $lang->task->statusList[$task->status];
        }
        ?>
      </td>
      <td class='a-left nobr'>
        <?php 
        if($task->storyID)
        {
            if(common::hasPriv('story', 'view')) echo html::a($this->createLink('story', 'view', "storyid=$task->storyID"), $task->storyTitle);
            else echo $task->storyTitle;
        }
        ?>
      </td>
      <td>
        <?php common::printLink('task', 'edit',   "taskid=$task->id", $lang->edit);?>
        <?php 
        if($browseType == 'needconfirm')
        {
            common::printLink('task', 'confirmStoryChange', "taskid=$task->id", $lang->confirm, 'hiddenwin');
        }
        else
        {
            common::printLink('task', 'delete', "projectID=$task->project&taskid=$task->id", $lang->delete, 'hiddenwin');
        }
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='a-right'><?php echo $pager;?></div>
</div>  
<script language='Javascript'>$('#<?php echo $browseType;?>').addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
