<?php
/**
 * The start file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
<div id='mainContent' class='main-content'>
  <?php if(!empty($task->team) && key($task->team) != $this->app->user->account):?>
  <div class="alert with-icon">
    <i class="icon-info-sign"></i>
    <div class="content">
      <p><?php echo $lang->task->deniedNotice;?></p>
    </div>
  </div>
  <?php else:?>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $task->id;?></span>
        <?php echo isonlybody() ? $task->name : html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name);?>
        <small><?php echo $lang->arrow . $lang->task->start;?></small>
      </div>
    </div>
    <form method='post' target='hiddenwin' onsubmit='return checkLeft();'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->task->realStarted;?></th>
          <td class='w-p25-f'><div class='datepicker-wrapper datepicker-date'><?php echo html::input('realStarted', $task->realStarted == '0000-00-00' ? helper::today() : $task->realStarted, "class='form-control form-date'");?></div></td>
          <td></td>
        </tr>  
        <tr>
          <th><?php echo $lang->task->consumed;?></th>
          <td><div class='input-group'><?php echo html::input('consumed', $task->consumed, "class='form-control' autocomplete='off'");?> <span class='input-group-addon'><?php echo $lang->task->hour;?></span></div></td><td></td>
        </tr>  
        <tr>
          <th><?php echo $lang->task->left;?></th>
          <td><div class='input-group'><?php echo html::input('left', $task->left, "class='form-control' autocomplete='off'");?> <span class='input-group-addon'><?php echo $lang->task->hour;?></span></div></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='2'>
            <?php echo html::submitButton($lang->task->start);?>
            <?php echo html::linkButton($lang->goback, $this->session->taskList);?>
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
