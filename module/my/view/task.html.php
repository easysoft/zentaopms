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
    echo html::a(inlink('task', "type=assignedTo"),  "<span class='text'>{$lang->my->taskMenu->assignedToMe}</span>", '', "class='btn btn-link" . ($type == 'assignedTo' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('task', "type=openedBy"),    "<span class='text'>{$lang->my->taskMenu->openedByMe}</span>",   '', "class='btn btn-link" . ($type == 'openedBy'   ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('task', "type=finishedBy"),  "<span class='text'>{$lang->my->taskMenu->finishedByMe}</span>", '', "class='btn btn-link" . ($type == 'finishedBy' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('task', "type=closedBy"),    "<span class='text'>{$lang->my->taskMenu->closedByMe}</span>",   '', "class='btn btn-link" . ($type == 'closedBy'   ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('task', "type=canceledBy"),  "<span class='text'>{$lang->my->taskMenu->canceledByMe}</span>", '', "class='btn btn-link" . ($type == 'canceledBy' ? ' btn-active-text' : '') . "'");
    ?>
  </div>
</div>
<div id="mainContent">
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
          <th class='c-pri'>     <?php common::printOrderLink('pri',         $orderBy, $vars, $lang->priAB);?></th>
          <th class='c-project'> <?php common::printOrderLink('project',     $orderBy, $vars, $lang->task->project);?></th>
          <th class='c-name'>    <?php common::printOrderLink('name',        $orderBy, $vars, $lang->task->name);?></th>
          <th class='c-user'>    <?php common::printOrderLink('openedBy',    $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='c-user'>    <?php common::printOrderLink('assignedTo',  $orderBy, $vars, $lang->task->assignedTo);?></th>
          <th class='c-user'>    <?php common::printOrderLink('finishedBy',  $orderBy, $vars, $lang->task->finishedBy);?></th>
          <th class='c-hours'>   <?php common::printOrderLink('estimate',    $orderBy, $vars, $lang->task->estimateAB);?></th>
          <th class='c-hours'>   <?php common::printOrderLink('consumed',    $orderBy, $vars, $lang->task->consumedAB);?></th>
          <th class='c-hours'>   <?php common::printOrderLink('left',        $orderBy, $vars, $lang->task->leftAB);?></th>
          <th class='c-date'>    <?php common::printOrderLink('deadline',    $orderBy, $vars, $lang->task->deadlineAB);?></th>
          <th class='c-status'>  <?php common::printOrderLink('status',      $orderBy, $vars, $lang->statusAB);?></th>
          <th class='c-actions-5'><?php echo $lang->actions;?></th>
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
          <td class='c-name'>
            <?php if(!empty($task->team))   echo '<span class="label label-badge label-light">' . $this->lang->task->multipleAB . '</span> ';?>
            <?php if(!empty($task->parent)) echo '<span class="label label-badge label-light">' . $this->lang->task->childrenAB . '</span> ';?>
            <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, null, "style='color: $task->color'");?>
          </td>
          <td class='c-user'><?php echo zget($users, $task->openedBy);?></td>
          <td class='c-user'><?php echo zget($users, $task->assignedTo);?></td>
          <td class='c-user'><?php echo zget($users, $task->finishedBy);?></td>
          <td class='c-hours'><?php echo $task->estimate;?></td>
          <td class='c-hours'><?php echo $task->consumed;?></td>
          <td class='c-hours'><?php echo $task->left;?></td>
          <td class='c-date <?php if(isset($task->delay)) echo 'text-red';?>'><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
          <td class='c-status'><span class="status-<?php echo $task->status;?>"><span class="label label-dot"></span> <?php echo $lang->task->statusList[$task->status];?></span></td>
          <td class='c-actions'>
            <?php
            common::printIcon('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $task, 'list', 'hand-right', '', 'iframe', true);
            common::printIcon('task', 'start',    "taskID=$task->id", $task, 'list', 'play', '', 'iframe', true);
            common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);
            common::printIcon('task', 'finish',   "taskID=$task->id", $task, 'list', 'checked', '', 'iframe', true);
            common::printIcon('task', 'close',    "taskID=$task->id", $task, 'list', 'off', '', 'iframe', true);
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($tasks):?>
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
          echo html::commonButton($lang->close, "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
      }
      ?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php js::set('listName', 'tasktable')?>
<?php include '../../common/view/footer.html.php';?>
