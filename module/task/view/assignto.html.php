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
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <?php if(!empty($task->team) && $task->assignedTo != $this->app->user->account):?>
    <div class="alert with-icon">
      <i class="icon-exclamation-sign"></i>
      <div class="content">
        <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->transfer);?></p>
      </div>
    </div>
    <?php else:?>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo isonlybody() ? ("<span title='$task->name'>" . $task->name . '</span>') : html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name);?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . (empty($task->team) ? $lang->task->assign : $lang->task->transfer);?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo empty($task->team) ? $lang->task->assign : $lang->task->transferTo;?></th>
          <td class='w-p25-f'><?php echo html::select('assignedTo', $members, empty($task->team) ? $task->assignedTo : $task->nextUser, "class='form-control chosen'");?></td><td></td>
        </tr>  
        <?php if($task->status != 'done' and $task->status != 'closed'):?>
        <tr>
          <th><?php echo $lang->task->left;?></th>
          <td><div class='input-group'><?php echo html::input('left', $task->left, "class='form-control'");?> <span class='input-group-addon'><?php echo $lang->task->hour;?></span></div></td><td></td>
        </tr>  
        <?php endif;?>
        <tr class='hide'>
          <th><?php echo $lang->task->status;?></th>
          <td><?php echo html::hidden('status', $task->status);?></td>
        </tr>
        <?php $this->printExtendFields($task, 'table', 'columns=2');?>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control w-p98'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::linkButton($lang->goback, $this->session->taskList);?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <?php include '../../common/view/action.html.php';?>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
