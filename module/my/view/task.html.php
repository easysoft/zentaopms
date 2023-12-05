<?php
/**
 * The task view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: task.html.php 5101 2013-07-12 00:44:27Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<?php $this->app->loadLang('task');?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>"; ?>
    <?php
    foreach($lang->my->featureBar[$app->rawMethod]['task'] as $param => $name)
    {
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=$param"),   "<span class='text'>{$name}</span>"   . ($type == $param ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == $param   ? ' btn-active-text' : '') . "'");
    }
    ?>
  </div>
  <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->my->byQuery;?></a>
</div>
<div id="mainContent">
  <?php $dataModule = $app->rawMethod == 'work' ? 'workTask' : 'contributeTask';?>
  <div class="cell<?php if($type == 'bySearch') echo ' show';?>" id="queryBox" data-module=<?php echo $dataModule;?>></div>
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->task->noTask;?></span></p>
  </div>
  <?php else:?>
  <form id='myTaskForm' class="main-table table-task skip-iframe-modal" method="post">
    <?php
    $canBatchEdit      = (common::hasPriv('task', 'batchEdit')  and $type == 'assignedTo');
    $canBatchClose     = (common::hasPriv('task', 'batchClose') and $type != 'closedBy');
    $canFinish         = common::hasPriv('task', 'finish');
    $canClose          = common::hasPriv('task', 'close');
    $canRecordEstimate = common::hasPriv('task', 'recordWorkhour');
    $canEdit           = common::hasPriv('task', 'edit');
    $canBatchCreate    = common::hasPriv('task', 'batchCreate');
    ?>
    <div class="table-responsive">
      <table class="table has-sort-head table-fixed" id='taskTable'>
        <?php $vars = "mode=$mode&type=$type&param=myQueryID&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
        <?php $type = $type == 'bySearch' ? $this->session->myTaskType : $type;?>
        <thead>
          <tr>
            <th class="c-id">
              <?php if($canBatchEdit or $canBatchClose):?>
              <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                <label></label>
              </div>
              <?php endif;?>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->task->name);?></th>
            <th class='c-pri' title=<?php echo $lang->task->pri;?>><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
            <th class='c-status'><?php common::printOrderLink('status',  $orderBy, $vars, $lang->statusAB);?></th>
            <th class='c-project'><?php common::printOrderLink('project',   $orderBy, $vars, $lang->my->projects);?></th>
            <th class='c-project'><?php common::printOrderLink('execution', $orderBy, $vars, $lang->task->execution);?></th>
            <?php if($type != 'assignedTo'): ?>
            <th class='c-user assigned-title'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->task->assignedTo);?></th>
            <?php endif;?>
            <th class='c-date text-center'><?php common::printOrderLink('deadline', $orderBy, $vars, $lang->task->deadlineAB);?></th>
            <th class='c-hours estimate'><?php common::printOrderLink('estimate', $orderBy, $vars, $lang->task->estimateAB);?></th>
            <th class='c-hours consumed'><?php common::printOrderLink('consumed', $orderBy, $vars, $lang->task->consumedAB);?></th>
            <th class='c-hours'><?php common::printOrderLink('left',     $orderBy, $vars, $lang->task->leftAB);?></th>
            <?php if($type != 'openedBy'): ?>
            <th class='c-user-short'><?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->task->openedByAB);?></th>
            <?php endif;?>
            <?php if($type != 'finishedBy'): ?>
            <th class='c-user'><?php common::printOrderLink('finishedBy', $orderBy, $vars, $lang->task->finishedByAB);?></th>
            <?php endif;?>
            <th class="c-actions-6 text-center"><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody id='myTaskList'>
          <?php foreach($tasks as $task):?>
          <?php $canBeChanged = common::canBeChanged('task', $task);?>
          <tr data-id='<?php echo $task->id;?>' data-status='<?php echo $task->status?>' data-estimate='<?php echo $task->estimate?>' data-consumed='<?php echo $task->consumed?>' data-left='<?php echo $task->left?>'>
            <td class="c-id">
              <?php if($canBatchEdit or $canBatchClose):?>
              <div class="checkbox-primary">
                <input type='checkbox' name='taskIDList[]' value='<?php echo $task->id;?>' <?php if(!$canBeChanged) echo 'disabled';?>/>
                <label></label>
              </div>
              <?php endif;?>
              <?php printf('%03d', $task->id);?>
            </td>
            <td class='c-name <?php if(!empty($task->children)) echo 'has-child';?>' title='<?php echo $task->name?>'>
              <?php if(!empty($task->team)) echo '<span class="label label-badge label-light">' . $this->lang->task->multipleAB . '</span> ';?>
              <?php
              $tab = empty($task->executionMultiple) ? 'project' : 'execution';
              if($task->parent > 0)
              {
                  echo '<span class="label label-badge label-light">' . $this->lang->task->childrenAB . '</span> ' . html::a($this->createLink('task', 'view', "taskID=$task->id", '', '', $task->project), $task->parentName . ' / '. $task->name, '', "title='{$task->parentName} / {$task->name}' data-app='$tab'");
              }
              else
              {
                  $onlybody = $task->executionType == 'kanban' ?  true : '';
                  $class    = $task->executionType == 'kanban' ? 'iframe' : '';
                  echo html::a($this->createLink('task', 'view', "taskID=$task->id", '', $onlybody, $task->project), $task->name, null, "class='$class' data-width='80%' style='color: $task->color' data-app='$tab'");
              }
              ?>
              <?php if(!empty($task->children)) echo '<a class="task-toggle" data-id="' . $task->id . '"><i class="icon icon-angle-right"></i></a>';?>
            </td>
            <td class="c-pri"><span class='label-pri <?php echo 'label-pri-' . $task->pri;?>' title='<?php echo zget($lang->task->priList, $task->pri);?>'><?php echo zget($lang->task->priList, $task->pri);?></span></td>
            <td class='c-status'>
              <?php $storyChanged = (!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion and !in_array($task->status, array('cancel', 'closed')));?>
              <?php $storyChanged ? print("<span class='status-story status-changed'>{$this->lang->my->storyChanged}</span>") : print("<span class='status-task status-{$task->status}'> " . $this->processStatus('task', $task) . "</span>");?>
            </td>
            <td class='c-project' title="<?php echo $task->projectName?>">
              <?php echo ($task->projectName and $task->project) ? html::a($this->createLink('project', 'index', "projectID=$task->project"), $task->projectName) : '';?>
            </td>
            <td class='c-project' title="<?php if($task->executionMultiple) echo $task->executionName;?>">
              <?php if($task->executionMultiple) echo html::a($this->createLink('execution', 'task', "executionID=$task->execution"), $task->executionName, '');?>
            </td>
            <?php if($type != 'assignedTo'): ?>
            <td class="c-assignedTo has-btn" title="<?php echo zget($users, $task->assignedTo);?>"> <?php $this->task->printAssignedHtml($task, $users);?></td>
            <?php endif;?>
            <td class="text-center <?php echo isset($task->delay) ? 'delayed' : '';?>"><?php if(substr($task->deadline, 0, 4) > 0) echo '<span>' . substr($task->deadline, 5, 6) . '</span>';?></td>
            <td class='c-hours' title="<?php echo round($task->estimate, 1) . ' ' . $lang->execution->workHour;?>"><?php echo round($task->estimate, 1) . $lang->execution->workHourUnit;?></td>
            <td class='c-hours' title="<?php echo round($task->consumed, 1) . ' ' . $lang->execution->workHour;?>"><?php echo round($task->consumed, 1) . $lang->execution->workHourUnit;?></td>
            <td class='c-hours' title="<?php echo round($task->left,     1) . ' ' . $lang->execution->workHour;?>"><?php echo round($task->left,     1) . $lang->execution->workHourUnit;?></td>
            <?php if($type != 'openedBy'): ?>
            <td class='c-user'><?php echo zget($users, $task->openedBy);?></td>
            <?php endif;?>
            <?php if($type != 'finishedBy'): ?>
            <td class='c-user'><?php echo zget($users, $task->finishedBy);?></td>
            <?php endif;?>
            <td class='c-actions'>
              <?php
              if($canBeChanged)
              {
                  if($task->needConfirm)
                  {
                      $this->lang->task->confirmStoryChange = $this->lang->confirm;
                      common::printIcon('task', 'confirmStoryChange', "taskid=$task->id", '', 'list', '', 'hiddenwin', '', '', '', '', $task->project);
                  }
                  else
                  {
                      $attr       = isset($kanbanList[$task->execution]) ? "disabled" : '';
                      $canStart   = ($task->status != 'pause' and common::hasPriv('task', 'start'));
                      $canRestart = ($task->status == 'pause' and common::hasPriv('task', 'restart'));
                      if($task->status != 'pause') common::printIcon('task', 'start', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
                      if($task->status == 'pause') common::printIcon('task', 'restart', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
                      common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
                      common::printIcon('task', 'close',  "taskID=$task->id", $task, 'list', '', '', 'iframe', true);

                      if(($canStart or $canRestart or $canFinish or $canClose) and ($canRecordEstimate or $canEdit or $canBatchCreate))
                      {
                          echo "<div class='dividing-line'></div>";
                      }

                      common::printIcon('task', 'recordWorkhour', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);
                      common::printIcon('task', 'edit', "taskID=$task->id", $task, 'list', '', '', 'iframe', true, "data-width='95%'");
                      common::printIcon('task', 'batchCreate', "executionID=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id&ifame=true", $task, 'list', 'split', '', 'iframe', true, "data-width='95%' $attr", $this->lang->task->children);
                  }
              }
              ?>
            </td>
          </tr>
          <?php if(!empty($task->children)):?>
            <?php $i = 0;?>
            <?php foreach($task->children as $key => $child):?>
            <?php $class  = $i == 0 ? ' table-child-top' : '';?>
            <?php $class .= ($i + 1 == count($task->children)) ? ' table-child-bottom' : '';?>
            <tr class='table-children<?php echo $class;?> parent-<?php echo $task->id;?>' data-id='<?php echo $child->id?>' data-status='<?php echo $child->status?>' data-estimate='<?php echo $child->estimate?>' data-consumed='<?php echo $child->consumed?>' data-left='<?php echo $child->left?>'>
              <td class="c-id">
                <?php if($canBatchEdit or $canBatchClose):?>
                <div class="checkbox-primary">
                  <input type='checkbox' name='taskIDList[]' value='<?php echo $child->id;?>' <?php if(!$canBeChanged) echo 'disabled';?>/>
                  <label></label>
                </div>
                <?php endif;?>
                <?php printf('%03d', $child->id);?>
              </td>
              <td class='c-name' title='<?php echo $child->name?>'>
                <?php $tab = empty($child->executionMultiple) ? 'project' : 'execution';?>
                <?php if($child->parent > 0) echo '<span class="label label-badge label-light">' . $this->lang->task->childrenAB . '</span> ';?>
                <?php echo html::a($this->createLink('task', 'view', "taskID=$child->id", '', '', $child->project), $child->name, null, "style='color: $child->color' data-app='$tab'");?>
              </td>
              <td class="c-pri"><span class='label-pri <?php echo 'label-pri-' . $child->pri;?>' title='<?php echo zget($lang->task->priList, $child->pri);?>'><?php echo zget($lang->task->priList, $child->pri);?></span></td>
              <td class='c-status'>
                <?php $storyChanged = (!empty($child->storyStatus) and $child->storyStatus == 'active' and $child->latestStoryVersion > $child->storyVersion and !in_array($child->status, array('cancel', 'closed')));?>
                <?php !empty($storyChanged) ? print("<span class='status-story status-changed'>{$this->lang->my->storyChanged}</span>") : print("<span class='status-task status-{$child->status}'> " . $this->processStatus('task', $child) . "</span>");?>
              </td>
              <td class='c-project' title="<?php echo $task->projectName;?>">
                <?php echo ($task->projectName and $task->project) ? html::a($this->createLink('project', 'view', "projectID=$task->project"), $task->projectName) : '';?>
              </td>
              <td class='c-project' title="<?php if($child->executionMultiple) echo $child->projectName;?>">
                <?php if($child->executionMultiple) echo html::a($this->createLink('execution', 'task', "executionID=$child->project"), $child->executionName, '');?>
              </td>
              <?php if($type != 'assignedTo'): ?>
              <td class="c-assignedTo has-btn" title="<?php echo zget($users, $child->assignedTo);?>"> <?php $this->task->printAssignedHtml($child, $users);?></td>
              <?php endif;?>
              <td class="text-center <?php echo isset($child->delay) ? 'delayed' : '';?>"><?php if(substr($child->deadline, 0, 4) > 0) echo '<span>' . substr($child->deadline, 5, 6) . '</span>';?></td>
              <td class='c-hours' title="<?php echo round($child->estimate, 1) . ' ' . $lang->execution->workHour;?>"><?php echo round($child->estimate, 1) . ' ' . $lang->execution->workHourUnit;?></td>
              <td class='c-hours' title="<?php echo round($child->consumed, 1) . ' ' . $lang->execution->workHour;?>"><?php echo round($child->consumed, 1) . ' ' . $lang->execution->workHourUnit;?></td>
              <td class='c-hours' title="<?php echo round($child->left,     1) . ' ' . $lang->execution->workHour;?>"><?php echo round($child->left,     1) . ' ' . $lang->execution->workHourUnit;?></td>
              <?php if($type != 'openedBy'): ?>
              <td class='c-user'><?php echo zget($users, $child->openedBy);?></td>
              <?php endif;?>
              <?php if($type != 'finishedBy'): ?>
              <td class='c-user'><?php echo zget($users, $child->finishedBy);?></td>
              <?php endif;?>
              <td class='c-actions'>
                <?php
                if($canBeChanged)
                {
                    if($child->needConfirm)
                    {
                        $this->lang->task->confirmStoryChange = $this->lang->confirm;
                        common::printIcon('task', 'confirmStoryChange', "taskid=$child->id", '', 'list', '', 'hiddenwin');
                    }
                    else
                    {
                        $canStart   = ($child->status != 'pause' and common::hasPriv('task', 'start'));
                        $canRestart = ($child->status == 'pause' and common::hasPriv('task', 'restart'));
                        if($child->status != 'pause') common::printIcon('task', 'start', "taskID=$child->id", $child, 'list', '', '', 'iframe', true);
                        if($child->status == 'pause') common::printIcon('task', 'restart', "taskID=$child->id", $child, 'list', '', '', 'iframe', true);
                        common::printIcon('task', 'finish', "taskID=$child->id", $child, 'list', '', '', 'iframe', true);
                        common::printIcon('task', 'close',  "taskID=$child->id", $child, 'list', '', '', 'iframe', true);

                        if(($canStart or $canRestart or $canFinish or $canClose) and ($canRecordEstimate or $canEdit or $canBatchCreate))
                        {
                            echo "<div class='dividing-line'></div>";
                        }

                        common::printIcon('task', 'recordWorkhour', "taskID=$child->id", $child, 'list', 'time', '', 'iframe', true);
                        common::printIcon('task', 'edit', "taskID=$child->id", $child, 'list', '', '', 'iframe', true, "data-width='95%'");
                        common::printIcon('task', 'batchCreate', "executionID=$child->execution&storyID=$child->story&moduleID=$child->module&taskID=$child->id&iframe=true", $child, 'list', 'split', '', 'iframe', true, '', $this->lang->task->children);
                    }
                }
                ?>
              </td>
            </tr>
            <?php $i ++;?>
            <?php endforeach;?>
            <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div class="table-footer">
      <?php if($canBatchClose or $canBatchEdit):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
      <?php
      if($canBatchEdit)
      {
          $actionLink = $this->createLink('task', 'batchEdit');
          $misc       = "data-form-action='$actionLink'";
          echo html::commonButton($lang->edit, $misc);
      }

      if($canBatchClose)
      {
          $actionLink = $this->createLink('task', 'batchClose', null, '', '', $task->project);
          echo html::commonButton($lang->close, "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"");
      }
      ?>
      </div>
      <div class="table-statistic"><?php echo $summary;?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php js::set('tasks', $tasks);?>
<script>
$(function()
{
    /* Update table summary text. */
    var checkedSummary = '<?php echo $lang->execution->checkedSummary?>';
    var pageSummary    = '<?php echo $lang->execution->pageSummary?>';

    $('#myTaskForm').table(
    {
        hot: true,
        replaceId: 'myTaskList',
        statisticCreator: function(table)
        {
            var $table = table.getTable();
            var $checkedRows = $table.find(table.isDataTable ? '.datatable-row-left.checked' : 'tbody>tr.checked');
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var checkedTotal = $checkedRows.length;
            var $rows = checkedTotal ? $checkedRows : $table.find(table.isDataTable ? '.datatable-rows .datatable-row-left' : 'tbody>tr');

            var checkedWait     = 0;
            var checkedDoing    = 0;
            var checkedEstimate = 0;
            var checkedConsumed = 0;
            var checkedLeft     = 0;
            var taskIdList      = [];

            $rows.each(function()
            {
                var $row = $(this);
                if ($originTable)
                {
                    $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');
                }
                var data = $row.data();
                taskIdList.push(data.id);

                var status = data.status;
                if(status === 'wait') checkedWait++;
                if(status === 'doing') checkedDoing++;

                var canStatistics = false;
                if(!$row.hasClass('table-children'))
                {
                    canStatistics = true;
                }
                else
                {
                    var parentID = 0;
                    var classes  = $row.attr('class').split(' ');
                    for(i in classes)
                    {
                        if(classes[i].indexOf('parent-') >= 0) parentID = classes[i].replace('parent-', '');
                    }

                    if(parentID && taskIdList.indexOf(parseInt(parentID)) < 0) canStatistics = true;
                }

                if(canStatistics)
                {
                    checkedEstimate += Number(data.estimate);
                    checkedConsumed += Number(data.consumed);
                    if(status != 'cancel' && status != 'closed') checkedLeft += Number(data.left);
                }
            });
            return (checkedTotal ? checkedSummary : pageSummary).replace('%total%', $rows.length).replace('%wait%', checkedWait)
              .replace('%doing%', checkedDoing)
              .replace('%estimate%', checkedEstimate.toFixed(1))
              .replace('%consumed%', checkedConsumed.toFixed(1))
              .replace('%left%', checkedLeft.toFixed(1));
        }
    })
});
</script>
<?php include '../../common/view/footer.html.php';?>
