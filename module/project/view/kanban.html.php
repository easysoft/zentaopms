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
<?php include './taskheader.html.php';?> 
<style>
<?php foreach($colorList as $status => $color):?>
<?php echo ".board-bug-$status, .board-task-$status {background: " . ($color ? $color : '#000') . ";}\n"?>
<?php endforeach?>
</style>
<?php
$taskCols = array('wait', 'doing', 'pause', 'done');
if($allCols) $taskCols = array('wait', 'doing', 'pause', 'done', 'cancel', 'closed');
$account = $this->app->user->account
?>
<div id='kanban'>
  <table class='boards-layout table' id='kanbanHeader'>
    <thead>
      <tr>
        <?php $hasGroupCol = (($type == 'story' and count($stories) > 0) or $type != 'story');?>
        <?php if($hasGroupCol):?>
        <th class='w-p15 col-story'>
          <div class='dropdown inline-block'>
            <a data-toggle='dropdown' href='javascript:;'>
              <?php if($type == 'story'):?>
              <?php echo $lang->project->orderList[$orderBy]?>
              <?php else:?>
              <?php echo $lang->task->$type;?>
              <?php endif;?>
               <span class='icon-caret-down'></span>
            </a>
            <ul class='dropdown-menu text-left'>
              <?php foreach ($lang->project->orderList as $key => $value):?>
              <li <?php echo ($type == 'story' and $orderBy == $key) ? " class='active'" : '' ?>>
                <?php echo html::a($this->createLink('project', 'kanban', "projectID=$projectID&type=story&orderBy=$key"), $value);?>
              </li>
              <?php endforeach;?>
              <?php echo "<li" . ($type == 'assignedTo' ? " class='active'" : '') . ">" . html::a(inlink('kanban', "project=$projectID&type=assignedTo"), $lang->project->groups['assignedTo']) . "</li>";?>
              <?php echo "<li" . ($type == 'finishedBy' ? " class='active'" : '') . ">" . html::a(inlink('kanban', "project=$projectID&type=finishedBy"), $lang->project->groups['finishedBy']) . "</li>";?>
            </ul>
          </div>
        </th>
        <?php endif;?>
        <?php $lastCol = array_pop($taskCols);?>
        <?php foreach($taskCols as $col):?>
        <th class='col-<?php echo $col?>'><?php echo $lang->task->statusList[$col];?></th>
        <?php endforeach;?>
        <th class='col-<?php echo $lastCol?>'><?php echo $lang->task->statusList[$lastCol];?>
          <div class='actions'>
            <div class="dropdown">
              <button type="button" class="btn btn-mini btn-link dropdown-toggle" data-toggle="dropdown">
                <span class="icon-ellipsis-h"></span>
              </button>
              <ul class="dropdown-menu pull-right">
                <?php echo '<li>' . html::a($this->createLink('project', 'ajaxKanbanSetting', "projectID=$projectID"), "<i class='icon-cog'></i> " . $lang->project->kanbanSetting, '', "class='iframe'") . '</li>';?>
                <?php if(common::hasPriv('project', 'printKanban')) echo '<li>' . html::a('###', "<i class='icon-print'></i> " . $lang->project->printKanban, '', "id='printKanban' title='{$lang->project->printKanban}'") . '</li>';?>
              </ul>
            </div>
          </div>
        </th>
      </tr>
    </thead>
  </table>
  <?php $taskCols[] = $lastCol;?>
  <table class='boards-layout table active-disabled table-bordered' id='kanbanWrapper'>
    <thead>
      <tr>
        <?php if($hasGroupCol):?>
        <th class='w-p15 col-story'> </th>
        <?php endif;?>
        <?php foreach($taskCols as $col):?><th class='col-<?php echo $col?>'></th><?php endforeach;?>
      </tr>
    </thead>
    <tbody>
      <?php $rowIndex = 0; ?>
      <?php foreach($kanbanGroup as $groupKey => $group):?>
      <?php if(count(get_object_vars($group)) == 0) continue;?>
      <tr data-id='<?php echo $rowIndex++?>'>
        <?php if($hasGroupCol):?>
        <td class='col-story'>
          <?php if($groupKey != 'nokey'):?>
          <?php if($type == 'story'):?>
          <?php $story = $group;?>
          <?php $class = "stage-{$story->stage}";?>
          <?php if($showOption) $class .= ' show-info';?>
          <?php if($story->assignedTo == $account) $class .= ' inverse';?>
          <div class='board board-story <?php echo $class;?>' data-id='<?php echo $story->id;?>'>
            <div class='board-title'>
              <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id", '', true), $story->title, '', 'class="kanbanFrame" title="' . $story->title . '"');?>
              <div class='board-actions'>
                <button type='button' class='btn btn-mini btn-link btn-info-toggle'><i class='icon-angle-down'></i></button>
                <div class='dropdown'>
                  <button type='button' class='btn btn-mini btn-link dropdown-toggle' data-toggle='dropdown'>
                    <span class='icon-ellipsis-h'></span>
                  </button>
                  <div class='dropdown-menu' style='left:-30px;width:80px'>
                    <?php
                    echo (common::hasPriv('task', 'create')) ? html::a($this->createLink('task', 'create', "projectID=$story->project&storyID=$story->story&moduleID=$story->module", '', true), $lang->project->wbs, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('task', 'batchCreate')) ? html::a($this->createLink('task', 'batchCreate', "projectID=$story->project&storyID=$story->story&iframe=true", '', true), $lang->project->batchWBS, '', "class='kanbanFrame' data-width='95%'") : '';
                    echo (common::hasPriv('project', 'unlinkStory')) ? html::a($this->createLink('project', 'unlinkStory', "projectID=$story->project&storyID=$story->story&confirm=no", '', true), $lang->project->unlinkStory, 'hiddenwin') : '';
                    echo (common::hasPriv('story', 'activate') and storyModel::isClickable($story, 'activate')) ? html::a($this->createLink('story', 'activate', "storyID=$story->id", '', 'true'), $lang->story->activate, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('story', 'close')    and storyModel::isClickable($story, 'close')) ? html::a($this->createLink('story', 'close', "storyID=$story->id", '', 'true'), $lang->story->close, '', "class='kanbanFrame'") : '';
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class='board-footer clearfix'>
              <span class='story-id board-id' title='<?php echo $lang->story->id?>'><?php echo $story->id?></span> 
              <span class='story-pri pri-<?php echo $story->pri?>' title='<?php echo $lang->story->pri?>'></span>
              <span class='story-stage' title='<?php echo $lang->story->stage?>'><?php echo $lang->story->stageList[$story->stage];?></span>
              <div class='pull-right story-estimate' title='<?php echo $lang->story->estimate?>'><?php echo $story->estimate . 'h ';?></div>
            </div>
          </div>
          <?php else:?>
          <div class='board board-<?php echo $type?>' data-id='<?php echo $groupKey?>'>
            <div class='board-title'>
              <?php echo zget($realnames, $groupKey);?>
            </div>
          </div>
          <?php endif;?>
          <?php endif;?>
        </td>
        <?php endif;?>
        <?php foreach($taskCols as $col):?>
        <td class='col-droppable col-<?php echo $col?>' data-id='<?php echo $col?>'>
        <?php if(!empty($group->tasks[$col])):?>
          <?php foreach($group->tasks[$col] as $task):?>
          <?php $class = "board-task-{$col}";?>
          <?php if($showOption) $class .= ' show-info';?>
          <?php if($task->assignedTo == $account) $class .= ' inverse';?>
          <div class='board board-task <?php echo $class;?>' data-id='<?php echo $task->id?>' id='task-<?php echo $task->id?>'>
            <div class='board-title'>
              <?php
              if(isset($task->delay))
              {
                  $labelClass = $task->status == 'doing' ? 'label-delay-doing' : 'label-delay-wait';
                  echo "<span class='label label-badge {$labelClass}'>{$lang->task->delayed}</span>";
              }
              $childrenAB = empty($task->parent) ? '' : "<span class='label'>" . $lang->task->childrenAB . '</span> ';
              echo html::a($this->createLink('task', 'view', "taskID=$task->id", '', true), $childrenAB . $task->name, '', 'class="kanbanFrame" title="' . $task->name . '"');
              ?>
              <div class='board-actions'>
                <button type='button' class='btn btn-mini btn-link btn-info-toggle'><i class='icon-angle-down'></i></button>
                <div class='dropdown'>
                  <button type='button' class='btn btn-mini btn-link dropdown-toggle' data-toggle='dropdown'>
                    <span class='icon-ellipsis-h'></span>
                  </button>
                  <div class='dropdown-menu' style='left:-15px'>
                    <?php
                    echo (common::hasPriv('task', 'assignTo') and taskModel::isClickable($task, 'assignTo')) ? html::a($this->createLink('task', 'assignTo', "projectID=$task->project&taskID=$task->id", '', 'true'), $lang->task->assignTo, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('task', 'start')    and taskModel::isClickable($task, 'start'))    ? html::a($this->createLink('task', 'start',    "taskID=$task->id", '', 'true'), $lang->task->start, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('task', 'finish')   and taskModel::isClickable($task, 'finish')) ? html::a($this->createLink('task', 'finish', "taskID=$task->id", '', 'true'), $lang->task->finish, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('task', 'cancel')   and taskModel::isClickable($task, 'cancel')) ? html::a($this->createLink('task', 'cancel', "taskID=$task->id", '', 'true'), $lang->task->cancel, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('task', 'activate') and taskModel::isClickable($task, 'activate')) ? html::a($this->createLink('task', 'activate', "taskID=$task->id", '', 'true'), $lang->task->activate, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('task', 'close')    and taskModel::isClickable($task, 'close')) ? html::a($this->createLink('task', 'close', "taskID=$task->id", '', 'true'), $lang->task->close, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('task', 'edit')     and taskModel::isClickable($task, 'edit')) ? html::a($this->createLink('task', 'edit', "taskID=$task->id", '', 'true'), $lang->task->edit, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('task', 'delete')   and taskModel::isClickable($task, 'delete')) ? html::a($this->createLink('task', 'delete', "project=$task->project&taskID=$task->id"), $lang->task->delete, 'hiddenwin') : '';
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class='board-footer clearfix'>
              <span class='task-id board-id' title='<?php echo $lang->task->id?>'><?php echo $task->id?></span> 
              <span class='task-pri pri-<?php echo $task->pri?>' title='<?php echo $lang->task->pri?>'></span>
              <span class="task-assignedTo" title='<?php echo $lang->task->assignedTo?>'>
                <?php echo html::a($this->createLink('task', 'assignTo', "projectID=$task->project&taskID=$task->id", '', true), '<i class="icon-hand-right"></i>', '', "class='kanbanFrame'")?>
                <small><?php echo zget($realnames, $task->assignedTo, $task->assignedTo);?></small>
              </span>
              <div class='pull-right'>
                <span class='task-left' title='<?php echo $lang->task->left?>'><?php echo $task->left . 'h ';?></span>
              </div>
            </div>
          </div>
          <?php endforeach?>
        <?php endif?>
        <?php if(!empty($group->bugs[$col])):?>
          <?php foreach($group->bugs[$col] as $bug):?>
          <?php $class = "board-bug-{$col}";?>
          <?php if($showOption) $class .= ' show-info';?>
          <?php if($bug->assignedTo == $account) $class .= ' inverse';?>
          <div class='board board-bug <?php echo $class;?>' data-id='<?php echo $bug->id?>' id='bug-<?php echo $bug->id?>'>
            <div class='board-title'>
              <i class="icon-bug"></i> 
              <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', 'class="kanbanFrame" title="' . $bug->title . '"');?>
              <div class='board-actions'>
                <button type='button' class='btn btn-mini btn-link btn-info-toggle'><i class='icon-angle-down'></i></button>
                <div class='dropdown'>
                  <button type='button' class='btn btn-mini btn-link dropdown-toggle' data-toggle='dropdown'>
                    <span class='icon-ellipsis-h'></span>
                  </button>
                  <div class='dropdown-menu' style='left:-15px'>
                    <?php
                    echo (common::hasPriv('bug', 'assignTo') and bugModel::isClickable($bug, 'assignTo')) ? html::a($this->createLink('bug', 'assignTo', "bugID=$bug->id", '', 'true'), $lang->bug->assignTo, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('bug', 'resolve')  and bugModel::isClickable($bug, 'resolve'))  ? html::a($this->createLink('bug', 'resolve',  "bugID=$bug->id", '', 'true'), $lang->bug->resolve, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('bug', 'activate') and bugModel::isClickable($bug, 'activate')) ? html::a($this->createLink('bug', 'activate', "bugID=$bug->id", '', 'true'), $lang->bug->activate, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('bug', 'close')    and bugModel::isClickable($bug, 'close'))    ? html::a($this->createLink('bug', 'close',    "bugID=$bug->id", '', 'true'), $lang->bug->close, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('bug', 'edit')     and bugModel::isClickable($bug, 'edit'))     ? html::a($this->createLink('bug', 'edit',     "bugID=$bug->id", '', 'true'), $lang->bug->edit, '', "class='kanbanFrame'") : '';
                    echo (common::hasPriv('bug', 'delete')   and bugModel::isClickable($bug, 'delete'))   ? html::a($this->createLink('bug', 'delete',   "bugID=$bug->id"), $lang->bug->delete, 'hiddenwin') : '';
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class='board-footer clearfix'>
              <span class='bug-id board-id' title='<?php echo $lang->bug->id?>'><?php echo $bug->id?></span> 
              <span class='bug-pri pri-<?php echo $bug->pri?>' title='<?php echo $lang->bug->pri?>'></span>
              <span class="bug-assignedTo" title='<?php echo $lang->bug->assignedTo?>'>
                <?php echo html::a($this->createLink('bug', 'assignTo', "bugID=$bug->id", '', true), '<i class="icon-hand-right"></i>', '', "class='kanbanFrame'")?>
                <small><?php echo zget($realnames, $bug->assignedTo, $bug->assignedTo);?></small>
              </span>
            </div>
          </div>
          <?php endforeach?>
        <?php endif?>
        </td>
        <?php endforeach;?>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<script>
var projectID = <?php echo $projectID?>;
$('#kanbanTab').addClass('active');
</script>
<?php include '../../common/view/footer.html.php';?>
