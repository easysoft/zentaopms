<?php
/**
 * The record file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     task
 * @version     $Id: record.html.php 935 2013-01-08 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('foldEffort', $lang->task->foldEffort);?>
<?php js::set('unfoldEffort', $lang->task->unfoldEffort);?>
<?php js::set('taskID', $task->id);?>
<?php if(!$this->task->canOperateEffort($task) and empty($myOrders)):?>
<style>#mainContent {min-height: unset;}</style>
<?php endif;?>
<?php $members = $task->members;?>
<?php
$confirmRecord = $lang->task->confirmRecord;
if(!empty($members) && $task->mode == 'linear')
{
    $nextAccount = '';
    $isCurrent   = false;
    foreach($task->team as $taskTeam)
    {
        if($isCurrent)
        {
            $nextAccount = $taskTeam->account;
            break;
        }
        if($task->assignedTo == $taskTeam->account and $taskTeam->account == $app->user->account and $taskTeam->status != 'done') $isCurrent = true;
    }
    if($nextAccount) $confirmRecord = sprintf($lang->task->confirmTransfer, zget($users, $nextAccount));
}
?>
<?php js::set('confirmRecord',    $confirmRecord);?>
<?php js::set('noticeSaveRecord', $lang->task->noticeSaveRecord);?>
<?php js::set('today', helper::today());?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo isonlybody() ? ("<span title='$task->name'>" . $task->name . '</span>') : html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name);?>
      </h2>
      <ul class='nav nav-default hours'>
        <li><span><?php echo $lang->task->estimate;?></span> </li>
        <li><span class='estimateTotally'><?php echo $task->estimate . 'h';?></span></li>
        <li>，</li>
        <li><span><?php echo $lang->task->consumed;?></span> </li>
        <li><span class='consumedTotally'><?php echo $task->consumed . 'h';?></span></li>
      </ul>
      <?php if(!isonlybody()):?>
      <small><?php echo $lang->arrow . $lang->task->logEfforts;?></small>
      <?php endif;?>
    </div>
    <?php if(!empty($efforts)):?>
    <?php if(!empty($task->team) and $task->mode == 'linear'):?>
    <?php include __DIR__ . '/lineareffort.html.php';?>
    <?php else:?>
    <table class='table table-bordered table-fixed table-recorded has-sort-head taskEffort'>
      <thead>
        <tr class='text-center'>
          <?php $vars = "taskID=$task->id&from=$from&orderBy=%s";?>
          <th class="w-120px"><?php common::printOrderLink('date', !strpos($orderBy, ',') ? $orderBy : 'date_asc', $vars, $lang->task->date);?></th>
          <th class="w-120px text-center"><?php echo $lang->task->recordedBy;?></th>
          <th class='text-left'><?php echo $lang->task->work;?></th>
          <th class="thWidth text-center"><?php echo $lang->task->consumedHours;?></th>
          <th class="thWidth text-center"><?php echo $lang->task->leftAB;?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1;?>
        <?php foreach($efforts as $effort):?>
        <?php $hidden = ($taskEffortFold and $i > 3) ? 'hidden' : ''?>
        <tr class="text-center <?php echo $hidden;?>">
          <td><?php echo $effort->date;?></td>
          <td><?php echo zget($users, $effort->account);?></td>
          <td class="text-left" title="<?php echo $effort->work;?>"><?php echo $effort->work;?></td>
          <td title="<?php echo $effort->consumed . ' ' . $lang->execution->workHour;?>"><?php echo $effort->consumed . ' ' . $lang->execution->workHourUnit;?></td>
          <td title="<?php echo $effort->left     . ' ' . $lang->execution->workHour;?>"><?php echo $effort->left     . ' ' . $lang->execution->workHourUnit;?></td>
          <td align='center' class='c-actions'>
            <?php
            $canOperateEffort = $this->task->canOperateEffort($task, $effort);
            $operateTips      = (empty($taks->team) && !$canOperateEffort) ? $lang->task->effortOperateTips : '';
            common::printIcon('task', 'editEstimate', "effortID=$effort->id", '', 'list', 'edit', '', 'showinonlybody', true, $canOperateEffort ? '' : 'disabled', $operateTips ? sprintf($operateTips, $lang->task->update) : '');
            common::printIcon('task', 'deleteEstimate', "effortID=$effort->id", '', 'list', 'trash', 'hiddenwin', 'showinonlybody', false, $canOperateEffort ? '' : 'disabled', $operateTips ? sprintf($operateTips, $lang->delete) : '');
            ?>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if(count($efforts) > 3):?>
    <div id='toggleFoldIcon'>
      <?php $icon     = $taskEffortFold ? 'icon-angle-down' : 'icon-angle-top'?>
      <?php $iconText = $taskEffortFold ? $lang->task->unfoldEffort : $lang->task->foldEffort;?>
      <span class='icon-border'><i class="icon <?php echo $icon;?>"></i></span>
      <span class='text'><?php echo $iconText;?></span>
    </div>
    <?php endif;?>
    <?php endif;?>
    <?php endif;?>
    <?php if(!$this->task->canOperateEffort($task) and empty($myOrders)):?>
    <div class="alert with-icon">
      <i class="icon-exclamation-sign"></i>
      <div class="content">
        <?php if(!isset($task->members[$app->user->account])):?>
        <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->logEfforts);?></p>
        <?php elseif($task->assignedTo != $app->user->account and $task->mode == 'linear'):?>
        <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->logEfforts);?></p>
        <?php endif;?>
      </div>
    </div>
    <?php else:?>
    <form id="recordForm" class='hidden' method='post' target='hiddenwin'>
      <?php
      $readonly = '';
      $left     = '';
      if(($task->assignedTo != $app->user->account or strpos('closed,cancel,done,pause', $task->status) !== false) and !empty($task->team) and $task->mode == 'linear' and !empty($myOrders))
      {
          $readonly      = ' readonly';
          $left          = 0;
          $reverseOrders = array_reverse($myOrders, true);
          foreach($reverseOrders as $order => $count) $reverseOrders[$order] = $order + 1;
      }
      ?>
      <table class='table table-form table-fixed table-record'>
        <thead>
          <tr class='text-center'>
            <th class="w-150px required"><?php echo $lang->task->date;?></th>
            <?php if($readonly):?>
            <th class="w-60px <?php if(count($reverseOrders) == 1) echo "hidden"?>"><?php echo $lang->task->teamOrder;?></th>
            <?php endif;?>
            <th><?php echo $lang->task->work;?></th>
            <th class="w-100px required"><?php echo $lang->task->consumedHours;?></th>
            <th class="w-100px <?php if(empty($readonly)) echo 'required'?>"><?php echo $lang->task->leftAB;?></th>
          </tr>
        </thead>
        <tbody>
          <?php for($i = 1; $i <= 5; $i++):?>
          <tr class="text-center">
            <td>
              <div class='input-group date-group'>
                <?php echo html::input("dates[$i]", helper::today(), "class='form-control text-center form-date'");?>
                <span class='input-group-addon'><i class='icon icon-calendar'></i></span>
              </div>
              <?php echo html::hidden("id[$i]", $i);?>
            </td>
            <?php if($readonly):?>
            <td class='<?php if(count($reverseOrders) == 1) echo "hidden"?>'><?php echo html::select("order[$i]", $reverseOrders, '', "class='form-control'")?></td>
            <?php endif;?>
            <td class="text-left"><?php echo html::textarea("work[$i]", '', "class='form-control' rows=1");?></td>
            <td>
              <div class='input-group'>
                <?php echo html::input("consumed[$i]", '', "class='form-control text-center'");?>
                <span class='input-group-addon'>h</span>
              </div>
            </td>
            <td>
              <div class='input-group'>
                <?php echo html::input("left[$i]", $left, "class='form-control text-center left' {$readonly}");?>
                <span class='input-group-addon'>h</span>
              </div>
            </td>
          </tr>
          <?php endfor;?>
        </tbody>
      </table>
      <div class='table-footer text-center form-actions'><?php echo html::submitButton() . html::backButton('', '', 'btn btn-wide');?></div>
    </form>
    <?php endif;?>
  </div>
</div>
<?php if(count($efforts) > 3):?>
<style>.taskEffort {margin-bottom: 5px;}</style>
<?php endif;?>
<?php include '../../common/view/footer.lite.html.php';?>
