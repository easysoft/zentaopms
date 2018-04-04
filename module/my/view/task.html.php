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
<main id="main">
  <div class="container">
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
              <th class="w-100px">
                <?php if($canBatchEdit or $canBatchClose):?>
                <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                  <label></label>
                </div>
                <?php endif;?>
                <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
              </th>
              <th class='w-pri'>   <?php common::printOrderLink('pri',         $orderBy, $vars, $lang->priAB);?></th>
              <th class='w-150px'> <?php common::printOrderLink('project',     $orderBy, $vars, $lang->task->project);?></th>
              <th>                 <?php common::printOrderLink('name',        $orderBy, $vars, $lang->task->name);?></th>
              <th class='w-user'>  <?php common::printOrderLink('openedBy',    $orderBy, $vars, $lang->openedByAB);?></th>
              <th class='w-user'>  <?php common::printOrderLink('assignedTo',  $orderBy, $vars, $lang->task->assignedTo);?></th>
              <th class='w-120px'> <?php common::printOrderLink('finishedBy',  $orderBy, $vars, $lang->task->finishedBy);?></th>
              <th class='w-hour'>  <?php common::printOrderLink('estimate',    $orderBy, $vars, $lang->task->estimateAB);?></th>
              <th class='w-70px'>  <?php common::printOrderLink('consumed',    $orderBy, $vars, $lang->task->consumedAB);?></th>
              <th class='w-hour'>  <?php common::printOrderLink('left',        $orderBy, $vars, $lang->task->leftAB);?></th>
              <th class='w-date'>  <?php common::printOrderLink('deadline',    $orderBy, $vars, $lang->task->deadlineAB);?></th>
              <th class='w-70px'>  <?php common::printOrderLink('status',      $orderBy, $vars, $lang->statusAB);?></th>
              <th class='c-actions-4'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($tasks as $task):?>
            <tr>
              <td class="c-id">
                <div class="checkbox-primary">
                  <?php if($canBatchEdit or $canBatchClose):?>
                  <input type='checkbox' name='taskIDList[]' value='<?php echo $task->id;?>' />
                  <label></label>
                  <?php endif;?>
                  <?php printf('%03d', $task->id);?>
                </div>
              </td>
              <td><span class='<?php echo 'pri' . $task->pri;?>'><?php echo $task->pri;?></span></td>
              <td class='nobr text-left'><?php echo html::a($this->createLink('project', 'browse', "projectid=$task->projectID"), $task->projectName);?></td>
              <td class='text-left nobr'>
                <?php if(!empty($task->team))   echo '<span class="label">' . $this->lang->task->multipleAB . '</span> ';?>
                <?php if(!empty($task->parent)) echo '<span class="label">' . $this->lang->task->childrenAB . '</span> ';?>
                <?php echo html::a($this->createLink('task', 'view', "taskID=$task->id"), $task->name, null, "style='color: $task->color'");?>
              </td>
              <td><?php echo zget($users, $task->openedBy);?></td>
              <td><?php echo zget($users, $task->assignedTo);?></td>
              <td><?php echo zget($users, $task->finishedBy);?></td>
              <td><?php echo $task->estimate;?></td>
              <td><?php echo $task->consumed;?></td>
              <td><?php echo $task->left;?></td>
              <td class='<?php if(isset($task->delay)) echo 'delayed';?>'><?php if(substr($task->deadline, 0, 4) > 0) echo $task->deadline;?></td>
              <td class='task-<?php echo $task->status;?>'><?php echo $lang->task->statusList[$task->status];?></td>
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
  </div>
</main>
<?php js::set('listName', 'tasktable')?>
<?php include '../../common/view/footer.html.php';?>
