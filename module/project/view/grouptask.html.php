<?php
/**
 * The task group view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: grouptask.html.php 4143 2013-01-18 07:01:06Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolbar pull-left">
    <?php if(!empty($tasks)):?>
    <div class="pull-left table-group-btns">
      <button type="button" class="btn btn-link group-collapse-all"><?php echo $lang->project->treeLevel['root'];?> <i class="icon-fold-all"></i></button>
      <button type="button" class="btn btn-link group-expand-all"><?php echo $lang->project->treeLevel['all'];?> <i class="icon-unfold-all"></i></button>
    </div>
    <?php endif;?>
    <?php if(isset($lang->project->groupFilter[$groupBy])):?>
    <?php foreach($lang->project->groupFilter[$groupBy] as $filterKey => $name):?>
    <?php
    $active = '';
    $name   = "<span class='text'>{$name}</span>";
    if($filterKey == $filter)
    {
        $name  .= " <span class='label label-light label-badge'>{$allCount}</span>";
        $active = 'btn-active-text';
    }
    ?>
    <?php echo html::a(inlink('grouptask', "projectID=$projectID&groupBy=$groupBy&filter=$filterKey"), $name, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php else:?>
    <?php echo html::a(inlink('grouptask', "projectID=$projectID&groupBy=$groupBy"), "<span class='text'>{$lang->project->allTasks}</span> <span class='label label-light label-badge'>{$allCount}</span>", '', "class='btn btn-link btn-active-text'");?>
    <?php endif;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php
    if(!isset($browseType)) $browseType = '';
    if(!isset($orderBy))    $orderBy = '';
    common::printIcon('task', 'report', "project=$projectID&browseType=$browseType", '', 'button', 'bar-chart muted');
    ?>
    <div class="btn-group">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export;?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <?php
        $misc = common::hasPriv('task', 'export') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('task', 'export') ? $this->createLink('task', 'export', "project=$projectID&orderBy=$orderBy&type=$browseType") : '#';
        echo "<li>" . html::a($link, $lang->story->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <div class="btn-group">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-import muted"></i> <span class="text"><?php echo $lang->import;?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <?php
        $misc = common::hasPriv('project', 'importTask') ? "class='import'" : "class=disabled";
        $link = common::hasPriv('project', 'importTask') ? $this->createLink('project', 'importTask', "project=$project->id") : '#';
        echo "<li>" . html::a($link, $lang->project->importTask, '', $misc) . "</li>";

        $misc = common::hasPriv('project', 'importBug') ? "class='import'" : "class=disabled";
        $link = common::hasPriv('project', 'importBug') ? $this->createLink('project', 'importBug', "project=$project->id") : '#';
        echo "<li>" . html::a($link, $lang->project->importBug, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php
    $checkObject = new stdclass();
    $checkObject->project = $projectID;
    $link = $this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''));
    if(common::hasPriv('task', 'create', $checkObject)) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->task->create}", '', "class='btn btn-primary'");
    ?>
  </div>
</div>
<div id='tasksTable' class='main-table' data-ride='table' data-checkable='false' data-group='true' data-hot='true'>
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->task->noTask;?></span>
      <?php if(common::hasPriv('task', 'create', $checkObject)):?>
      <span class="text-muted"><?php echo $lang->youCould;?></span>
      <?php echo html::a($this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : '')), "<i class='icon icon-plus'></i> " . $lang->task->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table table-grouped text-center">
    <thead>
      <tr class="<?php if($allCount) echo 'divider';?>">
        <th class="c-side text-left has-btn group-menu">
          <div class="dropdown">
            <a href="" data-toggle="dropdown" class="btn text-left btn-block btn-link clearfix">
              <span class='pull-left'><?php echo zget($lang->project->groups, $groupBy, null);?></span>
              <i class="icon icon-caret-down hl-primary text-primary pull-right"></i>
            </a>
            <ul class="dropdown-menu">
              <?php foreach($lang->project->groups as $key => $value):?>
              <?php
              if(empty($key)) continue;
              if($project->type == 'ops' && $key == 'story') continue;
              $active = $key == $groupBy ? "class='active'" : '';
              echo "<li $active>"; common::printLink('project', 'groupTask', "project=$projectID&groupBy=$key", $value); echo '</li>';
              ?>
              <?php endforeach;?>
            </ul>
          </div>
        </th>
        <th class="c-id-sm"><?php echo $lang->task->id;?></th>
        <th class="c-pri"><?php echo $lang->priAB;?></th>
        <th class="c-name"><?php echo $lang->task->name;?></th>
        <th class="c-status"><?php echo $lang->task->status;?></th>
        <th class="c-assign text-left"><?php echo $lang->task->assignedTo;?></th>
        <th class="c-user"><?php echo $lang->task->finishedBy;?></th>
        <th class="c-hours"><?php echo $lang->task->estimateAB;?></th>
        <th class="c-hours"><?php echo $lang->task->consumedAB;?></th>
        <th class="c-hours"><?php echo $lang->task->leftAB;?></th>
        <th class="c-num"><?php echo $lang->task->progress;?></th>
        <th class="c-type"><?php echo $lang->typeAB;?></th>
        <th class="c-date"><?php echo $lang->task->deadlineAB;?></th>
        <th class="c-actions-2"><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $groupIndex = 1;?>
      <?php foreach($tasks as $groupKey => $groupTasks):?>
      <?php
      $groupWait     = 0;
      $groupDone     = 0;
      $groupDoing    = 0;
      $groupClosed   = 0;
      $groupEstimate = 0.0;
      $groupConsumed = 0.0;
      $groupLeft     = 0.0;

      $groupName = $groupKey;
      if($groupBy == 'story') $groupName = empty($groupName) ? $this->lang->task->noStory : zget($groupByList, $groupKey);
      if($groupBy == 'assignedTo' and $groupName == '') $groupName = $this->lang->task->noAssigned;
      ?>
      <?php
      $groupSum = 0;
      foreach($groupTasks as $taskKey => $task)
      {
          if($groupBy == 'story')
          {
              if(!$task->parent)
              {
                  $groupEstimate += $task->estimate;
                  $groupConsumed += $task->consumed;
                  if($task->status != 'cancel' && $task->status != 'closed') $groupLeft += $task->left;
              }
          }
          else
          {
              $groupEstimate += $task->estimate;
              $groupConsumed += $task->consumed;

              if($groupBy == 'status' || ($task->status != 'cancel' && $task->status != 'closed')) $groupLeft += $task->left;
          }

          if($task->status == 'wait')   $groupWait++;
          if($task->status == 'doing')  $groupDoing++;
          if($task->status == 'done')   $groupDone++;
          if($task->status == 'closed') $groupClosed++;
      }
      $groupSum = count($groupTasks);
      ?>
      <?php $i = 0;?>
      <?php foreach($groupTasks as $task):?>
      <?php $assignedToClass = $task->assignedTo == $app->user->account ? "style='color:red'" : '';?>
      <?php $taskLink        = $this->createLink('task','view',"taskID=$task->id"); ?>
      <tr data-id='<?php echo $groupIndex?>' <?php if($groupIndex > 1 and $i == 0) echo "class='divider-top'";?>>
        <?php if($i == 0):?>
        <td rowspan='<?php echo $groupSum?>' class='c-side text-left group-toggle text-top'>
          <?php echo html::a('###', "<i class='icon-caret-down'></i> " . $groupName, '', "class='text-primary' title='$groupName'");?>
          <div class='groupSummary small'>

          <?php if($groupBy == 'assignedTo' and isset($members[$task->assignedTo])) printf($lang->project->memberHoursAB, $users[$task->assignedTo], $members[$task->assignedTo]->totalHours);?>
          <?php printf($lang->project->groupSummaryAB, $groupSum, $groupWait, $groupDoing, $groupEstimate, $groupConsumed, $groupLeft);?>
          </div>
        </td>
        <?php endif;?>
        <td class='c-id-sm'><?php echo sprintf('%03d', $task->id);?></td>
        <td class="c-pri"><span class='label-pri <?php echo 'label-pri-' . $task->pri?>' title='<?php echo zget($lang->task->priList, $task->pri, $task->pri);?>'><?php echo zget($lang->task->priList, $task->pri, $task->pri);?></span></td>
        <td class="c-name" title="<?php echo $task->name;?>">
          <?php
            if(!empty($task->team))   echo '<span class="label label-light label-badge">' . $lang->task->multipleAB . '</span> ';
            if(!empty($task->parent)) echo '<span class="label label-light label-badge">' . $lang->task->childrenAB . '</span> ';
            if(isset($task->children) && $task->children == true) echo '<span class="label">' . $lang->task->parentAB . '</span> ';
            if(!common::printLink('task', 'view', "task=$task->id", $task->name)) echo $task->name;
          ?>
        </td>
        <td class="c-status"><span class='status-<?php echo $task->status;?>'><span class="label label-dot"></span> <?php echo $lang->task->statusList[$task->status];?></span></td>
        <?php $assignedToRealName = $task->assignedToRealName ? $task->assignedToRealName : $lang->task->noAssigned;?>
        <td class="c-assign text-left has-btn"><?php echo html::a($this->createLink('task', 'assignTo', "projectID=$task->project&taskID=$task->id", 'html', true), '<i class="icon icon-hand-right"></i> ' . $assignedToRealName, '', "class='iframe btn btn-icon-left btn-sm' $assignedToClass");?></td>
        <td class='c-user'><?php echo zget($users, $task->finishedBy);?></td>
        <td class="c-hours em"><?php echo $task->estimate;?></td>
        <td class="c-hours em"><?php echo $task->consumed;?></td>
        <td class="c-hours em"><?php echo $task->left;?></td>
        <td class="c-num em"><?php echo $task->progress . '%';?></td>
        <td class="c-type"><?php echo zget($lang->task->typeList, $task->type);?></td>
        <td class='c-date <?php if(isset($task->delay)) echo 'delayed';?>'><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
        <td class="c-actions">
          <?php common::printIcon('task', 'edit', "taskid=$task->id", '', 'list');?>
          <?php common::printIcon('task', 'delete', "projectID=$task->project&taskid=$task->id", '', 'list', '', 'hiddenwin');?>
        </td>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
      <?php if($i != 0):?>
      <tr class='group-toggle group-summary hidden <?php if($groupIndex > 1) echo 'divider-top';?>' data-id='<?php echo $groupIndex?>'>
        <td class='c-side text-left'>
          <?php echo html::a('###', "<i class='icon-caret-right text-muted'></i> " . $groupName, '', "title='$groupName'");?>
        </td>
        <td colspan='13'>
          <div class="table-row segments-list">
          <?php if($groupBy == 'assignedTo' and isset($members[$task->assignedTo])) printf($lang->project->memberHours, $users[$task->assignedTo], $members[$task->assignedTo]->totalHours);?>
          <?php printf($lang->project->countSummary, $groupSum, $groupWait, $groupDoing);?>
          <?php printf($lang->project->timeSummary, $groupEstimate, $groupConsumed, $groupLeft);?>
          </div>
        </td>
      </tr>
      <?php endif;?>
      <?php $groupIndex ++;?>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
