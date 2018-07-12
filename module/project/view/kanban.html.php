<?php
/**
 * The kanban view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong 
 * @package     project
 * @version     $Id: kanban.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-right'>
    <?php echo html::a($this->createLink('project', 'ajaxKanbanSetting', "projectID=$projectID"), "<i class='icon-cog muted'></i> " . $lang->project->kanbanSetting, '', "class='iframe btn btn-link'");?>
    <?php if(common::hasPriv('project', 'printKanban')) echo html::a($this->createLink('project', 'printKanban', "projectID=$projectID"), "<i class='icon-printer muted'></i> " . $lang->project->printKanban, '', "class='iframe btn btn-link' id='printKanban' title='{$lang->project->printKanban}' data-width='500'");?>
    <?php 
    $link = $this->createLink('task', 'export', "project=$projectID&orderBy=$orderBy&type=$browseType");
    if(common::hasPriv('task', 'export')) echo html::a($link, "<i class='icon-import muted'></i> " . $lang->task->export, '', "class='btn btn-link iframe' data-width='700'");
    ?>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown' id='importAction'>
        <i class='icon-export muted'></i> <?php echo $lang->import ?>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu' id='importActionMenu'>
        <?php 
        $misc = common::hasPriv('project', 'importTask') ? '' : "class=disabled";
        $link = common::hasPriv('project', 'importTask') ?  $this->createLink('project', 'importTask', "project=$project->id") : '#';
        echo "<li>" . html::a($link, $lang->project->importTask, '', $misc) . "</li>";

        $misc = common::hasPriv('project', 'importBug') ? '' : "class=disabled";
        $link = common::hasPriv('project', 'importBug') ?  $this->createLink('project', 'importBug', "project=$project->id") : '#';
        echo "<li>" . html::a($link, $lang->project->importBug, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php 
    $checkObject = new stdclass();
    $checkObject->project = $projectID;
    $misc = common::hasPriv('task', 'create', $checkObject) ? "class='btn btn-primary'" : "class='btn btn-primary disabled'";
    $link = common::hasPriv('task', 'create', $checkObject) ?  $this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : '')) : '#';
    echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->task->create, '', $misc);
    ?>
  </div>
</div>
<style>
<?php foreach($colorList as $status => $color):?>
<?php echo "#kanban .c-board.s-$status{border-color: " . ($color ? $color : '#000') . ";}\n"?>
<?php endforeach?>
</style>
<?php
$taskCols = array('wait', 'doing', 'pause', 'done');
if($allCols) $taskCols = array('wait', 'doing', 'pause', 'done', 'cancel', 'closed');
$account = $this->app->user->account;
?>
<div id="kanban" class="main-table" data-ride="table" data-checkable="false" data-group="true">
  <?php
  $hasTask = false;
  foreach($kanbanGroup as $group)
  {
      if(count(get_object_vars($group)) > 0)
      {
          $hasTask = true;
          break;
      }
  }
  ?>
  <?php if(!$hasTask):?>
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
      <tr>
        <?php $hasGroupCol = (($type == 'story' and count($stories) > 0) or $type != 'story');?>
        <?php if($hasGroupCol):?>
        <th class="c-board c-side has-btn">
          <div class="dropdown">
            <?php $dropTitle = $type == 'story' ? $lang->project->orderList[$storyOrder] : $lang->task->$type;?>
            <button type="button" data-toggle="dropdown" class="btn btn-block btn-link"><?php echo $dropTitle;?> <span class="caret"></span></button>
            <ul class='dropdown-menu text-left'>
              <?php foreach($lang->project->orderList as $key => $value):?>
              <li <?php echo ($type == 'story' and $storyOrder == $key) ? " class='active'" : '' ?>>
                <?php echo html::a($this->createLink('project', 'kanban', "projectID=$projectID&type=story&orderBy=$key"), $value);?>
              </li>
              <?php endforeach;?>
              <?php echo "<li" . ($type == 'assignedTo' ? " class='active'" : '') . ">" . html::a(inlink('kanban', "project=$projectID&type=assignedTo"), $lang->project->groups['assignedTo']) . "</li>";?>
              <?php echo "<li" . ($type == 'finishedBy' ? " class='active'" : '') . ">" . html::a(inlink('kanban', "project=$projectID&type=finishedBy"), $lang->project->groups['finishedBy']) . "</li>";?>
            </ul>
          </div>
        </th>
        <?php endif;?>
        <?php foreach($taskCols as $col):?>
        <th class='c-board s-<?php echo $col?>'><?php echo $lang->task->statusList[$col];?></th>
        <?php endforeach;?>
      </tr>
    </thead>
    <tbody>
      <?php $rowIndex = 0; ?>
      <?php foreach($kanbanGroup as $groupKey => $group):?>
      <?php if(count(get_object_vars($group)) == 0) continue;?>
      <tr data-id='<?php echo $rowIndex++?>'>
        <?php if($hasGroupCol):?>
        <td class='c-side text-left'>
          <?php if($groupKey != 'nokey'):?>
          <?php if($type == 'story'):?>
          <?php $story = $group;?>
          <div class='board-story' data-id='<?php echo $story->id;?>'>
            <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', 'class="kanbaniframe group-title" title="' . $story->title . '"');?>
            <div class="small group-info">
              <span class='story-id board-id' title='<?php echo $lang->story->id?>'><?php echo $story->id?></span> 
              <span class='label-pri label-pri-<?php echo $story->pri?>' title='<?php echo $lang->story->pri?>'><?php echo zget($lang->story->priList, $story->pri);?></span>
              <span class='story-stage' title='<?php echo $lang->story->stage?>'><span class="label label-dot"></span> <?php echo $lang->story->stageList[$story->stage];?></span>
              <div class='pull-right text-muted story-estimate' title='<?php echo $lang->story->estimate?>'><?php echo $story->estimate . 'h ';?></div>
            </div>
          </div>
          <?php else:?>
          <div class='board-story' data-id='<?php echo $groupKey?>'><?php echo zget($realnames, $groupKey);?></div>
          <?php endif;?>
          <?php endif;?>
        </td>
        <?php endif;?>
        <td class="c-boards no-padding text-left" colspan="<?php echo count($taskCols);?>">
          <div class="boards-wrapper">
            <div class="boards">
              <?php foreach($taskCols as $col):?>
              <div class="board" data-type="<?php echo $col;?>">
                <?php if(!empty($group->tasks[$col])):?>
                <?php foreach($group->tasks[$col] as $task):?>
                <div class='board-item' data-id='<?php echo $task->id?>' id='task-<?php echo $task->id?>' data-type='task'>
                  <?php
                  $childrenAB = empty($task->parent) ? '' : "<span class='label label-light label-badge'>" . $lang->task->childrenAB . '</span> ';
                  echo html::a($this->createLink('task', 'view', "taskID=$task->id", '', true), "{$childrenAB}{$task->name}", '', 'class="title kanbaniframe" title="' . $task->name . '"');
                  ?>
                  <div class='info'>
                    <?php
                    $assignedToRealName = "<span>" . zget($realnames, $task->assignedTo) . "</span>";
                    if(empty($task->assignedTo)) $assignedToRealName = "<span class='text-primary'>{$lang->task->noAssigned}</span>";
                    echo html::a($this->createLink('task', 'assignTo', "projectID={$task->project}&taskID={$task->id}", '', true), '<i class="icon icon-hand-right"></i> ' . $assignedToRealName, '', 'class="btn btn-icon-left kanbaniframe task-assignedTo"');?>
                    <?php if(isset($task->delay)):?>
                    <span class="status-delayed"> <?php echo $lang->task->delayed;?></span>
                    <?php endif;?>
                    <small class="task-left" title='<?php echo $lang->task->left?>'><?php echo $task->left;?>h</small>
                  </div>
                </div>
                <?php endforeach?>
                <?php endif?>
                <?php if(!empty($group->bugs[$col])):?>
                <?php foreach($group->bugs[$col] as $bug):?>
                <div class='board-item' data-id='<?php echo $bug->id?>' id='bug-<?php echo $bug->id?>' data-type='bug'>
                  <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), "<i class='icon icon-sm icon-bug text-red'></i> #{$bug->id}{$bug->title}", '', 'class="title kanbaniframe" title="' . $bug->title . '"');?>
                  <div class='info'>
                    <?php
                    $assignedToRealName = "<span>" . zget($realnames, $bug->assignedTo) . "</span>";
                    if(empty($task->assignedTo)) $assignedToRealName = "<span class='text-primary text'>{$lang->task->noAssigned}</span>";
                    echo html::a($this->createLink('bug', 'assignTo', "bugID={$bug->id}", '', true), '<i class="icon icon-hand-right"></i> ' . $assignedToRealName, '', 'class="btn btn-icon-left kanbaniframe bug-assignedTo"');?>
                    <span class='status-<?php echo $bug->status;?>' title='<?php echo $lang->bug->status?>'><span class="label label-dot"></span> <?php echo zget($lang->bug->statusList, $bug->status);?></span>
                  </div>
                </div>
                <?php endforeach?>
                <?php endif?>
              </div>
              <?php endforeach;?>
            </div>
          </div>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php echo js::set('projectID', $projectID);?>
<?php include '../../common/view/footer.html.php';?>
