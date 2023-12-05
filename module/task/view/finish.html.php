<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('task', $task);?>
<?php js::set('consumedEmpty', $lang->task->error->consumedEmptyAB);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <?php if(!empty($task->members) and (!isset($task->members[$app->user->account]) or ($task->assignedTo != $app->user->account and $task->mode == 'linear'))):?>
    <div class="alert with-icon">
      <i class="icon-exclamation-sign"></i>
      <div class="content">
        <?php if($task->assignedTo != $app->user->account and $task->mode == 'linear'):?>
        <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->finish);?></p>
        <?php else:?>
        <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->finish);?></p>
        <?php endif;?>
      </div>
    </div>
    <?php else:?>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo isonlybody() ? ("<span title='$task->name'>" . $task->name . '</span>') : html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name);?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . $lang->task->finish;?></small>
        <?php endif;?>
      </div>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo !empty($task->team) ? $lang->task->common . $lang->task->consumed : $lang->task->hasConsumed;?></th>
          <td class='w-p25-f'><?php echo $task->consumed;?> <?php echo $lang->workingHour;?></td>
          <td></td>
        </tr>
        <?php if(!empty($task->team)):?>
        <tr>
          <th class='thWidth'><?php echo $lang->task->my . $lang->task->hasConsumed;?></th>
          <td class='w-p25-f'><?php echo (float)$task->myConsumed;?> <?php echo $lang->workingHour;?></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->task->currentConsumed;?></th>
          <td>
            <div class='input-group'><?php echo html::input('currentConsumed', 0, "class='form-control'");?> <span class='input-group-addon'><?php echo $lang->task->hour;?></span></div>
          </td>
          <td>
            <div class='table-row'>
              <div class='table-col strong w-80px text-right' style='padding-right:10px'><?php echo empty($task->team) ? $lang->task->consumed : $lang->task->myConsumed;?> </div>
              <div class='table-col'>
                <?php $consumed = empty($task->team) ? $task->consumed : (float)$task->myConsumed;?>
                <?php
                echo "<span id='totalConsumed'>" . (float)$consumed . "</span> " . $lang->workingHour . html::hidden('consumed', $consumed);
                js::set('consumed', $consumed);
                ?>
              </div>
            </div>
          </td>
        </tr>
        <tr>
        </tr>
        <tr class='<?php if($task->mode == 'multi') echo 'hidden'?>'>
          <th><?php echo $lang->task->assign;?></th>
          <td>
            <?php
            if(!empty($task->team) and $task->mode == 'linear')
            {
                echo zget($members, $task->nextBy) . html::hidden('assignedTo', $task->nextBy);
            }
            else
            {
                echo html::select('assignedTo', $members, $task->nextBy, "class='form-control chosen'");
            }
            ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->task->realStarted;?></th>
          <td>
            <div class='datepicker-wrapper'>
            <?php
            $realStarted = substr($task->realStarted, 0, 19);
            $readonly    = 'readonly';
            if(helper::isZeroDate($realStarted))
            {
                $realStarted = '';
                $readonly    = '';
            }
            ?>
            <?php echo html::input('realStarted', $realStarted, "class='form-control" . ($readonly ? '' : ' form-datetime') . "' $readonly");?>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->finishedDate;?></th>
          <td><div class='datepicker-wrapper'><?php echo html::input('finishedDate', helper::now(), "class='form-control form-datetime'");?></div></td><td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->task->status;?></th>
          <td><?php echo html::hidden('status', 'done');?></td>
        </tr>
        <?php $this->printExtendFields($task, 'table', 'columns=2');?>
        <tr>
          <th><?php echo $lang->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='w-p98'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton($lang->task->finish);?>
            <?php echo html::linkButton($lang->goback, $this->session->taskList, 'self', '', 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
