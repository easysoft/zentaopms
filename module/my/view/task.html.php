<?php
/**
 * The task view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>"; ?>
    <?php if($app->rawMethod == 'contribute'):?>
    <?php
    echo html::a(inlink($app->rawMethod, "mode=$mode&type=openedBy"),    "<span class='text'>{$lang->my->taskMenu->openedByMe}</span>"   . ($type == 'openedBy'   ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'openedBy'   ? ' btn-active-text' : '') . "'");
    echo html::a(inlink($app->rawMethod, "mode=$mode&type=finishedBy"),  "<span class='text'>{$lang->my->taskMenu->finishedByMe}</span>" . ($type == 'finishedBy' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'finishedBy' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink($app->rawMethod, "mode=$mode&type=closedBy"),    "<span class='text'>{$lang->my->taskMenu->closedByMe}</span>"   . ($type == 'closedBy'   ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'closedBy'   ? ' btn-active-text' : '') . "'");
    echo html::a(inlink($app->rawMethod, "mode=$mode&type=canceledBy"),  "<span class='text'>{$lang->my->taskMenu->canceledByMe}</span>" . ($type == 'canceledBy' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'canceledBy' ? ' btn-active-text' : '') . "'");
    ?>
    <?php endif;?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->task->noTask;?></span></p>
  </div>
  <?php else:?>
  <form id='myTaskForm' class="main-table table-task" method="post">
    <?php $canBatchEdit  = (common::hasPriv('task', 'batchEdit')  and $type == 'assignedTo');?>
    <?php $canBatchClose = (common::hasPriv('task', 'batchClose') and $type != 'closedBy');?>
    <table class="table has-sort-head table-fixed" id='taskTable'>
      <?php $vars = "mode=$mode&type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
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
          <th class='c-pri w-40px'>        <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
          <th class='c-name'>              <?php common::printOrderLink('name',       $orderBy, $vars, $lang->task->name);?></th>
          <th class='c-project w-120px'>   <?php common::printOrderLink('execution',  $orderBy, $vars, $lang->my->executions);?></th>
          <?php if($type != 'openedBy'): ?>
          <th class='c-user w-90px'>       <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
          <?php endif;?>
          <?php if($type != 'assignedTo'): ?>
          <th class='c-assignedTo w-110px'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->task->assignedTo);?></th>
          <?php endif;?>
          <?php if($type != 'finishedBy'): ?>
          <th class='c-user w-100px'>      <?php common::printOrderLink('finishedBy', $orderBy, $vars, $lang->task->finishedBy);?></th>
          <?php endif;?>
          <th class='c-hours w-50px'>      <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->task->estimateAB);?></th>
          <th class='c-hours w-50px'>      <?php common::printOrderLink('consumed',   $orderBy, $vars, $lang->task->consumedAB);?></th>
          <th class='c-hours w-50px'>      <?php common::printOrderLink('left',       $orderBy, $vars, $lang->task->leftAB);?></th>
          <th class='c-status w-70px'>     <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
          <th class='c-actions-6'>         <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
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
          <td class="c-pri"><span class='label-pri <?php echo 'label-pri-' . $task->pri;?>' title='<?php echo zget($lang->task->priList, $task->pri);?>'><?php echo zget($lang->task->priList, $task->pri);?></span></td>
          <td class='c-name <?php if(!empty($task->children)) echo 'has-child';?>' title='<?php echo $task->name?>'>
            <?php if(!empty($task->team))   echo '<span class="label label-badge label-light">' . $this->lang->task->multipleAB . '</span> ';?>
            <?php if($task->parent > 0) echo '<span class="label label-badge label-light">' . $this->lang->task->childrenAB . '</span> ';?>
            <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id", '', '', $task->project), $task->name, null, "style='color: $task->color' data-group='execution'");?>
            <?php if(!empty($task->children)) echo '<a class="task-toggle" data-id="' . $task->id . '"><i class="icon icon-angle-double-right"></i></a>';?>
          </td>
          <td class='c-project' title="<?php echo $task->executionName;?>"><?php echo html::a($this->createLink('execution', 'task', "executionID=$task->execution", '', '', $task->project), $task->executionName, '', "data-group='execution'");?></td>
          <?php if($type != 'openedBy'): ?>
          <td class='c-user'><?php echo zget($users, $task->openedBy);?></td>
          <?php endif;?>
          <?php if($type != 'assignedTo'): ?>
          <td class="c-assignedTo has-btn"> <?php $this->task->printAssignedHtml($task, $users);?></td>
          <?php endif;?>
          <?php if($type != 'finishedBy'): ?>
          <td class='c-user'><?php echo zget($users, $task->finishedBy);?></td>
          <?php endif;?>
          <td class='c-hours' title="<?php echo round($task->estimate, 1) . ' ' . $lang->execution->workHour;?>"><?php echo round($task->estimate, 1) . $lang->execution->workHourUnit;?></td>
          <td class='c-hours' title="<?php echo round($task->consumed, 1) . ' ' . $lang->execution->workHour;?>"><?php echo round($task->consumed, 1) . $lang->execution->workHourUnit;?></td>
          <td class='c-hours' title="<?php echo round($task->left,     1) . ' ' . $lang->execution->workHour;?>"><?php echo round($task->left,     1) . $lang->execution->workHourUnit;?></td>
          <td class='c-status'>
            <?php $storyChanged = (!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion and !in_array($task->status, array('cancel', 'closed')));?>
            <?php !empty($storyChanged) ? print("<span class='status-story status-changed'>{$this->lang->my->storyChanged}</span>") : print("<span class='status-task status-{$task->status}'> " . $this->processStatus('task', $task) . "</span>");?>
          </td>
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
                    if($task->status != 'pause') common::printIcon('task', 'start', "taskID=$task->id", $task, 'list', '', '', 'iframe', true, '', '', $task->project);
                    if($task->status == 'pause') common::printIcon('task', 'restart', "taskID=$task->id", $task, 'list', '', '', 'iframe', true, '', '', $task->project);
                    common::printIcon('task', 'close',  "taskID=$task->id", $task, 'list', '', '', 'iframe', true, '', '', $task->project);
                    common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list', '', '', 'iframe', true, '', '', $task->project);

                    common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true, '', '', $task->project);
                    common::printIcon('task', 'edit', "taskID=$task->id", $task, 'list', '', '', 'iframe', true, "data-width='95%'", '', $task->project);
                    common::printIcon('task', 'batchCreate', "executionID=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id&ifame=true", $task, 'list', 'split', '', 'iframe', true, "data-width='95%'", $this->lang->task->children, $task->project);
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
            <td class="c-pri"><span class='label-pri <?php echo 'label-pri-' . $child->pri;?>' title='<?php echo zget($lang->task->priList, $child->pri);?>'><?php echo zget($lang->task->priList, $child->pri);?></span></td>
            <td class='c-name' title='<?php echo $child->name?>'>
              <?php if($child->parent > 0) echo '<span class="label label-badge label-light">' . $this->lang->task->childrenAB . '</span> ';?>
              <?php echo html::a($this->createLink('task', 'view', "taskID=$child->id", '', '', $child->project), $child->name, null, "style='color: $child->color' data-group='project'");?>
            </td>
            <td class='c-project' title="<?php echo $child->projectName;?>"><?php echo html::a($this->createLink('execution', 'task', "executionID=$child->project", '', '', $child->project), $child->executionName, '', "data-group='execution'");?></td>
            <?php if($type != 'openedBy'): ?>
            <td class='c-user'><?php echo zget($users, $child->openedBy);?></td>
            <?php endif;?>
            <?php if($type != 'assignedTo'): ?>
            <td class="c-assignedTo has-btn"> <?php $this->task->printAssignedHtml($child, $users);?></td>
            <?php endif;?>
            <?php if($type != 'finishedBy'): ?>
            <td class='c-user'><?php echo zget($users, $child->finishedBy);?></td>
            <?php endif;?>
            <td class='c-hours' title="<?php echo round($child->estimate, 1) . ' ' . $lang->execution->workHour;?>"><?php echo round($child->estimate, 1) . ' ' . $lang->execution->workHourUnit;?></td>
            <td class='c-hours' title="<?php echo round($child->consumed, 1) . ' ' . $lang->execution->workHour;?>"><?php echo round($child->consumed, 1) . ' ' . $lang->execution->workHourUnit;?></td>
            <td class='c-hours' title="<?php echo round($child->left,     1) . ' ' . $lang->execution->workHour;?>"><?php echo round($child->left,     1) . ' ' . $lang->execution->workHourUnit;?></td>
            <td class='c-status'>
              <?php $storyChanged = (!empty($child->storyStatus) and $child->storyStatus == 'active' and $child->latestStoryVersion > $child->storyVersion and !in_array($child->status, array('cancel', 'closed')));?>
              <?php !empty($storyChanged) ? print("<span class='status-story status-changed'>{$this->lang->my->storyChanged}</span>") : print("<span class='status-task status-{$child->status}'> " . $this->processStatus('task', $child) . "</span>");?>
            </td>
            <td class='c-actions'>
              <?php
              if($canBeChanged)
              {
                  if($child->needConfirm)
                  {
                      $this->lang->task->confirmStoryChange = $this->lang->confirm;
                      common::printIcon('task', 'confirmStoryChange', "taskid=$child->id", '', 'list', '', 'hiddenwin', '', '', '', '', $child->project);
                  }
                  else
                  {
                      if($child->status != 'pause') common::printIcon('task', 'start', "taskID=$child->id", $child, 'list', '', '', 'iframe', true, '', '', $child->project);
                      if($child->status == 'pause') common::printIcon('task', 'restart', "taskID=$child->id", $child, 'list', '', '', 'iframe', true, '', '', $child->project);
                      common::printIcon('task', 'close',  "taskID=$child->id", $child, 'list', '', '', 'iframe', true, '', '', $child->project);
                      common::printIcon('task', 'finish', "taskID=$child->id", $child, 'list', '', '', 'iframe', true, '', '', $child->project);

                      common::printIcon('task', 'recordEstimate', "taskID=$child->id", $child, 'list', 'time', '', 'iframe', true, '', '', $child->project);
                      common::printIcon('task', 'edit',   "taskID=$child->id", $child, 'list', '', '', '', '', 'data-group="execution"', '', $child->project);
                      common::printIcon('task', 'batchCreate', "executionID=$child->execution&storyID=$child->story&moduleID=$child->module&taskID=$child->id&iframe=true", $child, 'list', 'split', '', 'iframe', true, 'data-group="execution"', $this->lang->task->children, $child->project);
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
