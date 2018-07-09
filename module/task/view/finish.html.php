<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <?php if(!empty($task->team) && $task->assignedTo != $this->app->user->account):?>
    <div class="alert with-icon">
      <i class="icon-exclamation-sign"></i>
      <div class="content">
        <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->finish);?></p>
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
          <th class='w-80px'><?php echo !empty($task->team) ? $lang->task->common . $lang->task->consumed : $lang->task->hasConsumed;?></th>
          <td class='w-p25-f'><?php echo $task->consumed;?> <?php echo $lang->workingHour;?></td>
          <td></td>
        </tr>
        <?php if(!empty($task->team)):?>
        <tr>
          <th class='w-80px'><?php echo $lang->task->hasConsumed;?></th>
          <td class='w-p25-f'><?php echo $task->myConsumed;?> <?php echo $lang->workingHour;?></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo empty($task->team) ? $lang->task->consumed : $lang->task->myConsumed;?></th>
          <td>
            <?php $consumed = empty($task->team) ? $task->consumed : $task->myConsumed;?>
            <div class='input-group'><?php echo html::input('consumed', $consumed, "class='form-control' autocomplete='off'");?> <span class='input-group-addon'><?php echo $lang->task->hour;?></span></div>
          </td>
        </tr>
        <tr>
          <th><?php echo empty($task->team) ? $lang->task->assign : $lang->task->transferTo;?></th>
          <td><?php echo html::select('assignedTo', $members, $task->nextBy, "class='form-control chosen'");?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->finishedDate;?></th>
          <td><div class='datepicker-wrapper'><?php echo html::input('finishedDate', helper::today(), "class='form-control form-date'");?></div></td><td></td>
        </tr>
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
            <?php echo html::submitButton($lang->task->finish, '', 'btn btn-wide btn-primary');?>
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
