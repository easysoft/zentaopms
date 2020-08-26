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
<?php js::set('statusMap', $statusMap);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-right'>
    <?php echo html::a($this->createLink('project', 'ajaxKanbanSetting', "projectID=$projectID"), "<i class='icon-cog muted'></i> " . $lang->project->kanbanSetting, '', "class='iframe btn btn-link'");?>
    <?php if(common::hasPriv('project', 'printKanban')) echo html::a($this->createLink('project', 'printKanban', "projectID=$projectID"), "<i class='icon-printer muted'></i> " . $lang->project->printKanban, '', "class='iframe btn btn-link' id='printKanban' title='{$lang->project->printKanban}' data-width='500'");?>
    <?php
    $link = $this->createLink('task', 'export', "project=$projectID&orderBy=$orderBy&type=kanban");
    if(common::hasPriv('task', 'export')) echo html::a($link, "<i class='icon-export muted'></i> " . $lang->task->export, '', "class='btn btn-link iframe export' data-width='700'");
    ?>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown' id='importAction'>
        <i class='icon-import muted'></i> <?php echo $lang->import ?>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu' id='importActionMenu'>
        <?php
        $misc = common::hasPriv('project', 'importTask') ? '' : "class=disabled";
        $link = common::hasPriv('project', 'importTask') ?  $this->createLink('project', 'importTask', "project=$project->id") : '#';
        echo "<li $misc>" . html::a($link, $lang->project->importTask, '', $misc) . "</li>";

        $misc = common::hasPriv('project', 'importBug') ? '' : "class=disabled";
        $link = common::hasPriv('project', 'importBug') ?  $this->createLink('project', 'importBug', "project=$project->id") : '#';
        echo "<li $misc>" . html::a($link, $lang->project->importBug, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php
    $checkObject = new stdclass();
    $checkObject->project = $projectID;
    $misc = common::hasPriv('task', 'create', $checkObject) ? "class='btn btn-primary iframe' data-width='1200px'" : "class='btn btn-primary disabled'";
    $link = common::hasPriv('task', 'create', $checkObject) ?  $this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''), '', true) : '#';
    echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->task->create, '', $misc);
    ?>
  </div>
</div>
<style>
<?php foreach($colorList as $status => $color):?>
<?php echo "#kanban .c-board.s-$status{border-color: " . ($color ? $color : '#000') . ";}\n"?>
<?php endforeach?>
</style>
<div id="kanban" class="main-table fade auto-fade-in" data-ride="table" data-checkable="false" data-group="true">
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
      <?php echo html::a($this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : '')), "<i class='icon icon-plus'></i> " . $lang->task->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table no-margin table-grouped text-center">
    <thead>
      <tr>
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
        <?php foreach($kanbanColumns as $col):?>
        <th class='c-board s-<?php echo $col?>'><?php echo zget($statusList, $col);?></th>
        <?php endforeach;?>
      </tr>
    </thead>
    <tbody>
      <?php $rowIndex = 0; ?>
      <?php foreach($kanbanGroup as $groupKey => $group):?>
      <?php if(count(get_object_vars($group)) == 0) continue;?>
      <tr data-id='<?php echo $rowIndex++?>'>
        <td class='c-side text-left'>
          <?php if($groupKey != 'nokey'):?>
          <?php if($type == 'story'):?>
          <?php $story = $group;?>
          <div class='board-story' data-id='<?php echo $story->id;?>'>
            <div class='board-title'>
              <?php
              if(common::hasPriv('story', 'view'))
              {
                  echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', 'class="kanbaniframe group-title" title="' . $story->title . '"');
              }
              else
              {
                  echo "<span class='group-title' title='{$story->title}'>{$story->title}</span>";
              }
              ?>
              <nav class='board-actions nav nav-default'>
                <li class='dropdown'>
                  <a href='javascript:;' data-toggle='dropdown' class='panel-action'><i class='icon icon-ellipsis-v'></i></a>
                  <ul class='dropdown-menu pull-right'>
                    <?php
                    $misc = "data-toggle='modal' data-type='iframe' data-width='95%'";
                    echo (common::hasPriv('task', 'create'))         ? '<li>' . html::a($this->createLink('task', 'create', "projectID=$story->project&storyID=$story->id&moduleID=$story->module", '', true), $lang->project->wbs, '', $misc) : '' . '</li>';
                    echo (common::hasPriv('task', 'batchCreate'))    ? '<li>' . html::a($this->createLink('task', 'batchCreate', "projectID=$story->project&storyID=$story->id&moduleID=0&taskID=0&iframe=true", '', true), $lang->project->batchWBS, '', $misc) : '' . '</li>';
                    echo (common::hasPriv('project', 'unlinkStory')) ? '<li>' . html::a($this->createLink('project', 'unlinkStory', "projectID=$story->project&storyID=$story->story&confirm=no", '', true), $lang->project->unlinkStory, 'hiddenwin') : '' . '</li>';
                    $misc = "data-toggle='modal' data-type='iframe'";
                    echo (common::hasPriv('story', 'close'))         ? '<li>' . html::a($this->createLink('story', 'close', "storyID=$story->id", '', true), $lang->story->close, '', $misc) : '' . '</li>';
                    ?>
                  </ul>
                </li>
              </nav>
            </div>
            <div class="small group-info">
              <span class='story-id board-id' title='<?php echo $lang->story->id?>'>#<?php echo $story->id?></span>
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
        <td class="c-boards no-padding text-left" colspan="<?php echo count($kanbanColumns);?>">
          <div class="boards-wrapper">
            <div class="boards">
              <?php foreach($kanbanColumns as $col):?>
              <div class="board" data-type="<?php echo $col;?>">
                <?php if(!empty($group->tasks[$col])):?>
                <?php foreach($group->tasks[$col] as $task):?>
                <?php $disabled = common::hasDBPriv($task, 'task') ? '' : 'disabled';?>
                <div class='board-item <?php echo $disabled;?>' data-id='<?php echo $task->id?>' id='task-<?php echo $task->id?>' data-type='task'>
                  <?php
                  $childrenAB = $task->parent > 0 ? "<span class='label label-light label-badge'>" . $lang->task->childrenAB . '</span> ' : '';
                  if(common::hasPriv('task', 'view'))
                  {
                      echo html::a($this->createLink('task', 'view', "taskID=$task->id", '', true), "{$childrenAB}{$task->name}", '', 'class="title kanbaniframe" title="' . $task->name . '"');
                  }
                  else
                  {
                      echo "<span class='title' title='{$task->name}'>{$childrenAB}{$task->name}</span>";
                  }
                  ?>
                  <div class='info'>
                    <?php
                    $assignedToRealName = "<span>" . zget($realnames, $task->assignedTo) . "</span>";
                    if(empty($task->assignedTo)) $assignedToRealName = "<span class='text-primary'>{$lang->task->noAssigned}</span>";
                    if(common::hasPriv('task', 'assignTo', $task))
                    {
                        echo html::a($this->createLink('task', 'assignTo', "projectID={$task->project}&taskID={$task->id}", '', true), '<i class="icon icon-hand-right"></i> ' . $assignedToRealName, '', 'class="btn btn-icon-left kanbaniframe task-assignedTo"');
                    }
                    else
                    {
                        echo "<span class='btn btn-icon-left task-assignedTo disabled'><i class='icon icon-hand-right'></i> {$assignedToRealName}</span>";
                    }
                    ?>
                    <?php if(isset($task->delay)):?>
                    <span class="status-task status-delayed"> <?php echo $lang->task->delayed;?></span>
                    <?php endif;?>
                    <small class="task-left" title='<?php echo $lang->task->left?>'><?php echo $task->left;?>h</small>
                  </div>
                </div>
                <?php endforeach?>
                <?php endif?>
                <?php if(!empty($group->bugs[$col])):?>
                <?php foreach($group->bugs[$col] as $bug):?>
                <div class='board-item' data-id='<?php echo $bug->id?>' id='bug-<?php echo $bug->id?>' data-type='bug'>
                  <?php
                    if(common::hasPriv('bug', 'view'))
                    {
                        echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), "<i class='icon icon-sm icon-bug text-red'></i> #{$bug->id}{$bug->title}", '', 'class="title kanbaniframe" title="' . $bug->title . '"');
                    }
                    else
                    {
                        echo "<span class='title' title='{$bug->title}'><i class='icon icon-sm icon-bug text-red'></i> #{$bug->id}{$bug->title}</span>";
                    }
                    ?>
                  <div class='info'>
                    <?php
                    $assignedToRealName = "<span>" . zget($realnames, $bug->assignedTo) . "</span>";
                    if(empty($bug->assignedTo)) $assignedToRealName = "<span class='text-primary'>{$lang->task->noAssigned}</span>";
                    if(common::hasPriv('bug', 'assignTo', $bug))
                    {
                        echo html::a($this->createLink('bug', 'assignTo', "bugID={$bug->id}", '', true), '<i class="icon icon-hand-right"></i> ' . $assignedToRealName, '', 'class="btn btn-icon-left kanbaniframe bug-assignedTo"');
                    }
                    else
                    {
                        echo "<span class='btn btn-icon-left bug-assignedTo disabled'><i class='icon icon-hand-right'></i> {$assignedToRealName}</span>";
                    }
                    ?>
                    <span class='status-bug status-<?php echo $bug->status;?>' title='<?php echo $lang->bug->status?>'><span class="label label-dot"></span> <?php echo zget($lang->bug->statusList, $bug->status);?></span>
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
