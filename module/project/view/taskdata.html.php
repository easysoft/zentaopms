<?php
/**
 * The task data view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
    <?php include '../../common/view/tablesorter.html.php';?>
    <?php $columns = 12; ?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable' id='taskList'>
      <thead>
        <tr>
          <th class='w-id {sorter:false}'>    <?php common::printOrderLink('id',           $orderBy, $vars, $lang->idAB);?></th>
          <th class='w-pri {sorter:false}'>   <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
          <th class='{sorter:false}'>         <?php common::printOrderLink('name',         $orderBy, $vars, $lang->task->name);?></th>
          <th class='w-100px {sorter:false}'> <?php common::printOrderLink('status',       $orderBy, $vars, $lang->statusAB);?></th>
          <th class='w-70px {sorter:false}'>  <?php common::printOrderLink('deadline',     $orderBy, $vars, $lang->task->deadlineAB);?></th>

          <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
          <?php $columns++; ?>
          <th class='w-id {sorter:false}'>    <?php common::printOrderLink('openedDate',   $orderBy, $vars, $lang->task->openedDateAB);?></th>
          <?php endif;?>

          <th class='w-user {sorter:false}'>  <?php common::printOrderLink('assignedTo',   $orderBy, $vars, $lang->task->assignedToAB);?></th>
          <th class='w-user {sorter:false}'>  <?php common::printOrderLink('finishedBy',   $orderBy, $vars, $lang->task->finishedByAB);?></th>

          <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
          <?php $columns++; ?>
          <th class='w-50px {sorter:false}'>  <?php common::printOrderLink('finishedDate', $orderBy, $vars, $lang->task->finishedDateAB);?></th>
          <?php endif;?>

          <th class='w-35px {sorter:false}'>  <?php common::printOrderLink('estimate',     $orderBy, $vars, $lang->task->estimateAB);?></th>
          <th class='w-50px {sorter:false}'>  <?php common::printOrderLink('consumed',     $orderBy, $vars, $lang->task->consumedAB);?></th>
          <th class='w-40px nobr {sorter:false}'>  <?php common::printOrderLink('left',    $orderBy, $vars, $lang->task->leftAB);?></th>
          <th class='w-50px' title='<?php echo $lang->task->progessTips?>'><?php echo $lang->task->progess;?></th>

          <?php if($project->type == 'sprint'): ?>
          <th class='w-100px {sorter:false}'><?php common::printOrderLink('story', $orderBy, $vars, $lang->task->story); ?></th>
          <?php $columns++; ?>
          <?php endif;?>

          <th class='w-150px {sorter:false}'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <?php if($tasks):?>
      <tbody>
      <?php foreach($tasks as $task):?>
      <?php $class = $task->assignedTo == $app->user->account ? 'style=color:red' : ''; ?>
      <tr class='text-center'>
        <td class='cell-id'>
          <input type='checkbox' name='taskIDList[]'  value='<?php echo $task->id;?>'/> 
          <?php if(!common::printLink('task', 'view', "task=$task->id", sprintf('%03d', $task->id))) printf('%03d', $task->id);?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->task->priList, $task->pri, $task->pri)?>'><?php echo $task->pri == '0' ? '' : zget($lang->task->priList, $task->pri, $task->pri);?></span></td>
        <td class='text-left' title="<?php echo $task->name?>">
          <?php if(isset($branchGroups[$task->product][$task->branch])) echo "<span title='{$lang->product->branchName[$task->productType]}' class='label label-branch label-badge'>" . $branchGroups[$task->product][$task->branch] . '</span>';?>
          <?php if($modulePairs and $task->module) echo "<span title='{$lang->task->module}' class='label label-info label-badge'>" . $modulePairs[$task->module] . '</span>';?>
          <?php 
          if(!common::printLink('task', 'view', "task=$task->id", $task->name, null, "style='color: $task->color'")) echo $task->name;
          if($task->fromBug) echo html::a($this->createLink('bug', 'view', "id=$task->fromBug"), "[BUG#$task->fromBug]", '_blank', "class='bug'");
          ?>
        </td>
        <td class="<?php echo $task->status;?>">
          <?php
          $storyChanged = ($task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion);
          if($storyChanged)
          {
                echo "(<span class='warning'>{$lang->story->changed}</span> ";
                echo html::a($this->createLink('task', 'confirmStoryChange', "taskID=$task->id"), $lang->confirm, 'hiddenwin');
                echo ")";
          }
          else
          {
              echo $lang->task->statusList[$task->status];
          }
          ?>
        </td>
        <td class="<?php if(isset($task->delay)) echo 'delayed';?>"><?php if(substr($task->deadline, 0, 4) > 0) echo substr($task->deadline, 5, 6);?></td>

        <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
        <td><?php echo substr($task->openedDate, 5, 6);?></td>
        <?php endif;?>

        <td <?php echo $class;?>><?php echo $task->assignedTo == 'closed' ? 'Closed' : $users[$task->assignedTo];?></td>
        <td><?php echo zget($users, $task->finishedBy, $task->finishedBy);?></td>

        <?php if($this->cookie->windowWidth > $this->config->wideSize):?>
        <td><?php echo substr($task->finishedDate, 5, 6);?></td>
        <?php endif;?>

        <td><?php echo $task->estimate;?></td>
        <td><?php echo $task->consumed;?></td>
        <td><?php echo $task->left;?></td>
        <td><?php echo $task->progess?>%</td>
        <?php
        if($project->type == 'sprint')
        {
            echo '<td class="text-left" title="' . $task->storyTitle . '">';
            if($task->storyID)
            {
              if(!common::printLink('story', 'view', "storyid=$task->storyID", $task->storyTitle)) print $task->storyTitle;
            }
            echo '</td>';
        }
        ?>
        <td class='text-right'>
        <?php
        common::printIcon('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $task, 'list', '', '', 'iframe', true);
        common::printIcon('task', 'start',    "taskID=$task->id", $task, 'list', '', '', 'iframe', true);

        common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);
        if($browseType == 'needconfirm')
        {
            $lang->task->confirmStoryChange = $lang->confirm;
            common::printIcon('task', 'confirmStoryChange', "taskid=$task->id", '', 'list', '', 'hiddenwin');
        }
        common::printIcon('task', 'finish',  "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
        common::printIcon('task', 'close',   "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
        common::printIcon('task', 'edit',    "taskID=$task->id", '', 'list');
        ?>
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <?php endif?>
