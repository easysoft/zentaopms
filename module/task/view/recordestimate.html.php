<?php
/**
 * The record file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     task
 * @version     $Id: record.html.php 935 2013-01-08 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<style>
.table-recorded thead{background: rgb(245 245 245);}
</style>
<?php $members = $task->members;?>
<?php js::set('confirmRecord',    (!empty($members) && $task->assignedTo != end($members)) ? $lang->task->confirmTransfer : $lang->task->confirmRecord);?>
<?php js::set('noticeSaveRecord', $lang->task->noticeSaveRecord);?>
<?php js::set('today', helper::today());?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo isonlybody() ? ("<span title='$task->name'>" . $task->name . '</span>') : html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name);?>
        <?php if(!isonlybody()):?>
        <small><?php echo $lang->arrow . $lang->task->logEfforts;?></small>
        <?php endif;?>
      </div>
    </div>
    <form id="recordForm" method='post' target='hiddenwin'>
      <?php if(count($estimates)):?>
      <table class='table table-bordered table-fixed table-recorded'>
        <thead>
          <tr class='text-center'>
            <th class="w-120px"><?php echo $lang->task->date;?></th>
            <th class="w-120px"><?php echo $lang->task->recordedBy;?></th>
            <th><?php echo $lang->comment;?></th>
            <th class="thWidth"><?php echo $lang->task->consumed;?></th>
            <th class="thWidth"><?php echo $lang->task->left;?></th>
            <th class='c-actions-2'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($estimates as $estimate):?>
          <tr class="text-center">
            <td><?php echo $estimate->date;?></td>
            <td><?php echo zget($users, $estimate->account);?></td>
            <td class="text-left" title="<?php echo $estimate->work;?>"><?php echo $estimate->work;?></td>
            <td title="<?php echo $estimate->consumed . ' ' . $lang->execution->workHour;?>"><?php echo $estimate->consumed . ' ' . $lang->execution->workHourUnit;?></td>
            <td title="<?php echo $estimate->left     . ' ' . $lang->execution->workHour;?>"><?php echo $estimate->left     . ' ' . $lang->execution->workHourUnit;?></td>
            <td align='center' class='c-actions'>
              <?php
              $canOperateEffort = $this->task->canOperateEffort($task, $estimate);
              common::printIcon('task', 'editEstimate', "estimateID=$estimate->id", '', 'list', 'edit', '', 'showinonlybody', true, $canOperateEffort ? '' : 'disabled');
              common::printIcon('task', 'deleteEstimate', "estimateID=$estimate->id", '', 'list', 'trash', 'hiddenwin', 'showinonlybody', false, ($canOperateEffort and ($task->mode != 'multi' or ($task->mode == 'linear' and $estimate->left > 0))) ? '' : 'disabled');
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php endif;?>
      <?php if(!$this->task->canOperateEffort($task)):?>
      <div class="alert with-icon">
        <i class="icon-exclamation-sign"></i>
        <div class="content">
          <?php if(strpos('|done|closed|cancel|pause|', $task->status) !== false):?>
          <p><?php echo sprintf($lang->task->deniedStatusNotice, '<strong>' . zget($this->lang->task->statusList, $task->status) . '</strong>');?></p>
          <?php elseif($task->assignedTo != $app->user->account and $task->mode == 'linear'):?>
          <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->logEfforts);?></p>
          <?php else:?>
          <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->logEfforts);?></p>
          <?php endif;?>
        </div>
      </div>
      <?php else:?>
      <table class='table table-form table-fixed table-record'>
        <thead>
          <tr class='text-center'>
            <th class="w-120px"><?php echo $lang->task->date;?></th>
            <th><?php echo $lang->comment;?></th>
            <th class="w-100px"><?php echo $lang->task->consumedAB;?></th>
            <th class="w-100px"><?php echo $lang->task->leftAB;?></th>
          </tr>
        </thead>
        <tbody>
          <?php for($i = 1; $i <= 5; $i++):?>
          <tr class="text-center">
            <?php echo html::hidden("id[$i]", $i);?>
            <td><?php echo html::input("dates[$i]", helper::today(), "class='form-control text-center form-date'");?></td>
            <td class="text-left"><?php echo html::textarea("work[$i]", '', "class='form-control' rows=1");?></td>
            <td>
              <div class='input-group'>
                <?php echo html::input("consumed[$i]", '', "class='form-control text-center'");?>
                <span class='input-group-addon'>h</span>
              </div>
            </td>
            <td>
              <div class='input-group'>
                <?php echo html::input("left[$i]", '', "class='form-control text-center left'");?>
                <span class='input-group-addon'>h</span>
              </div>
            </td>
          </tr>
          <?php endfor;?>
          <tr>
            <td colspan='4' class='text-center form-actions'><?php echo html::submitButton() . html::backButton('', '', 'btn btn-wide');?></td>
          </tr>
        </tbody>
      </table>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
