<?php
/**
 * The start file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: start.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('confirmFinish', $lang->task->confirmFinish);?>
<?php js::set('noticeTaskStart', $lang->task->noticeTaskStart);?>
<div id='mainContent' class='main-content'>
  <?php
  /* IF it is multi-task, the suspened can only be restarted by the current user who it is assigned to. */
  if(!empty($task->members) and (!isset($task->members[$app->user->account]) or ($task->assignedTo != $app->user->account and $task->mode == 'linear'))):
  ?>
  <div class="alert with-icon">
    <i class="icon-exclamation-sign"></i>
    <div class="content">
      <?php if($task->assignedTo != $app->user->account and $task->mode == 'linear'):?>
      <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->start);?></p>
      <?php else:?>
      <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->start);?></p>
      <?php endif;?>
    </div>
  </div>
  <?php else:?>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo isonlybody() ? ("<span title='$task->name'>" . $task->name . '</span>') : html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name);?>
        <?php if(!isonlybody()):?>
        <small><?php echo $lang->arrow . $lang->task->start;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin' <?php if($app->rawMethod == 'start') echo "onsubmit='return checkLeft();'"?>>
      <table class='table table-form'>
        <tr class='<?php if($task->mode == 'multi') echo 'hidden'?>'>
          <th class='w-90px'><?php echo $lang->task->assignedTo;?></th>
          <td class='w-p25-f'>
            <?php
            if($task->mode == 'linear')
            {
                echo zget($members, $assignedTo) . html::hidden('assignedTo', $assignedTo);
            }
            else
            {
                echo html::select('assignedTo', $members, $assignedTo, "class='form-control chosen'");
            }
            ?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th class='w-90px'><?php echo $lang->task->realStarted;?></th>
          <td class='w-p25-f'><div class='datepicker-wrapper datepicker-date'><?php echo html::input('realStarted', helper::isZeroDate($task->realStarted) ? helper::now() : $task->realStarted, "class='form-control form-datetime' data-picker-position='bottom-right'");?></div></td>
          <td></td>
        </tr>
        <tr>
          <?php
          $currentTeam = !empty($task->team) ? $this->task->getTeamByAccount($task->team) : '';
          $consumed    = !empty($currentTeam) ? (float)$currentTeam->consumed : $task->consumed;
          $lblConsumed = $lang->task->consumed;
          $readonly    = '';
          if($app->rawMethod == 'restart' and !empty($currentTeam))
          {
              $lblConsumed = $lang->task->myConsumed;
              $readonly    = 'readonly';
          }
          elseif($app->rawMethod == 'start' and $task->mode == 'linear')
          {
              $lblConsumed = $lang->task->myConsumed;
          }
          ?>
          <th><?php echo $lblConsumed;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('consumed', $consumed, "class='form-control' $readonly");?> <span class='input-group-addon'><?php echo $lang->task->hour;?></span>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->task->left;?></th>
          <td>
            <div class='input-group'>
              <?php $left = !empty($currentTeam) ? (float)$currentTeam->left : $task->left;?>
              <?php echo html::input('left', $left, "class='form-control'");?> <span class='input-group-addon'><?php echo $lang->task->hour;?></span>
            </div>
          </td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->task->status;?></th>
          <td><?php echo html::hidden('status', 'doing');?></td>
        </tr>
        <?php $this->printExtendFields($task, 'table', 'columns=2');?>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton($lang->task->start);?>
            <?php echo html::linkButton($lang->goback, $this->session->taskList, 'self', '', 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
