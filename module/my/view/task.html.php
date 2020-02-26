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
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    echo html::a(inlink('task', "type=assignedTo"),  "<span class='text'>{$lang->my->taskMenu->assignedToMe}</span>" . ($type == 'assignedTo' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'assignedTo' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('task', "type=openedBy"),    "<span class='text'>{$lang->my->taskMenu->openedByMe}</span>"   . ($type == 'openedBy'   ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'openedBy'   ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('task', "type=finishedBy"),  "<span class='text'>{$lang->my->taskMenu->finishedByMe}</span>" . ($type == 'finishedBy' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'finishedBy' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('task', "type=closedBy"),    "<span class='text'>{$lang->my->taskMenu->closedByMe}</span>"   . ($type == 'closedBy'   ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'closedBy'   ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('task', "type=canceledBy"),  "<span class='text'>{$lang->my->taskMenu->canceledByMe}</span>" . ($type == 'canceledBy' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'canceledBy' ? ' btn-active-text' : '') . "'");
    ?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->task->noTask;?></span></p>
  </div>
  <?php else:?>
  <form id='myTaskForm' class="main-table table-task" data-ride="table" method="post">
    <?php $canBatchEdit  = common::hasPriv('task', 'batchEdit');?>
    <?php $canBatchClose = (common::hasPriv('task', 'batchClose') and $type != 'closedBy');?>
    <table class="table has-sort-head table-fixed" id='tasktable'>
      <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
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
          <th class='c-pri w-40px'> <?php common::printOrderLink('pri',         $orderBy, $vars, $lang->priAB);?></th>
          <th class='c-project'>    <?php common::printOrderLink('project',     $orderBy, $vars, $lang->task->project);?></th>
          <th class='c-name'>       <?php common::printOrderLink('name',        $orderBy, $vars, $lang->task->name);?></th>
          <th class='c-user w-90px'><?php common::printOrderLink('openedBy',    $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-90px c-assignedTo'><?php common::printOrderLink('assignedTo',  $orderBy, $vars, $lang->task->assignedTo);?></th>
          <th class='c-user w-100px'><?php common::printOrderLink('finishedBy',  $orderBy, $vars, $lang->task->finishedBy);?></th>
          <th class='c-hours w-50px'><?php common::printOrderLink('estimate',    $orderBy, $vars, $lang->task->estimateAB);?></th>
          <th class='c-hours w-50px'>   <?php common::printOrderLink('consumed',    $orderBy, $vars, $lang->task->consumedAB);?></th>
          <th class='c-hours w-50px'>   <?php common::printOrderLink('left',        $orderBy, $vars, $lang->task->leftAB);?></th>
          <th class='c-status'>  <?php common::printOrderLink('status',      $orderBy, $vars, $lang->statusAB);?></th>
          <th class='c-actions-6'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tasks as $task):?>
        <tr>
          <td class="c-id">
            <?php if($canBatchEdit or $canBatchClose):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='taskIDList[]' value='<?php echo $task->id;?>' />
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $task->id);?>
          </td>
          <td class="c-pri"><span class='label-pri <?php echo 'label-pri-' . $task->pri;?>' title='<?php echo zget($lang->task->priList, $task->pri);?>'><?php echo zget($lang->task->priList, $task->pri);?></span></td>
          <td class='c-project' title="<?php echo $task->projectName;?>"><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->projectID"), $task->projectName);?></td>
          <td class='c-name' title='<?php echo $task->name?>'>
            <?php if(!empty($task->team))   echo '<span class="label label-badge label-light">' . $this->lang->task->multipleAB . '</span> ';?>
            <?php if($task->parent > 0) echo '<span class="label label-badge label-light">' . $this->lang->task->childrenAB . '</span> ';?>
            <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, null, "style='color: $task->color'");?>
          </td>
          <td class='c-user'><?php echo zget($users, $task->openedBy);?></td>
          <td class="c-assignedTo has-btn"> <?php $this->task->printAssignedHtml($task, $users);?></td>
          <td class='c-user'><?php echo zget($users, $task->finishedBy);?></td>
          <td class='c-hours'><?php echo $task->estimate;?></td>
          <td class='c-hours'><?php echo $task->consumed;?></td>
          <td class='c-hours'><?php echo $task->left;?></td>
          <td class='c-status'>
            <?php $storyChanged = (!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion and !in_array($task->status, array('cancel', 'closed')));?>
            <?php !empty($storyChanged) ? print("<span class='status-story status-changed'>{$this->lang->story->changed}</span>") : print("<span class='status-task status-{$task->status}'> " . $this->processStatus('task', $task) . "</span>");?>
          </td>
          <td class='c-actions'>
            <?php
            if($task->needConfirm)
            {
                $this->lang->task->confirmStoryChange = $this->lang->confirm;
                common::printIcon('task', 'confirmStoryChange', "taskid=$task->id", '', 'list', '', 'hiddenwin', 'btn-wide');
            }
            else
            {
                if($task->status != 'pause') common::printIcon('task', 'start', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
                if($task->status == 'pause') common::printIcon('task', 'restart', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
                common::printIcon('task', 'close',  "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
                common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);

                common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);
                common::printIcon('task', 'edit',   "taskID=$task->id", $task, 'list');
                common::printIcon('task', 'batchCreate', "project=$task->project&storyID=$task->story&moduleID=$task->module&taskID=$task->id&ifame=0", $task, 'list', 'treemap-alt', '', '', '', '', $this->lang->task->children);
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canBatchEdit or $canBatchClose):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
      <?php
      if($canBatchEdit)
      {
          $actionLink = $this->createLink('task', 'batchEdit', "projectID=0&orderBy=$orderBy");
          echo html::commonButton($lang->edit, "onclick=\"setFormAction('$actionLink')\"");

      }
      if($canBatchClose)
      {
          $actionLink = $this->createLink('task', 'batchClose');
          echo html::commonButton($lang->close, "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"");
      }
      ?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php js::set('listName', 'tasktable')?>
<?php include '../../common/view/footer.html.php';?>
