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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?> <strong><?php echo $task->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name, '_blank');?></strong>
    <small class='text-muted'> <?php echo $lang->task->start;?> <?php echo html::icon($lang->icons['start']);?></small>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin' onsubmit='return checkLeft();'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->task->realStarted;?></th>
      <td class='w-p25-f'><div class='datepicker-wrapper datepicker-date'><?php echo html::input('realStarted', $task->realStarted == '0000-00-00' ? helper::today() : $task->realStarted, "class='form-control form-date'");?></div></td><td></td>
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
      <th></th><td colspan='2'><?php echo html::submitButton($lang->task->start); ?></td>
    </tr>
  </table>
</form>
<div class='main'><?php include '../../common/view/action.html.php';?></div>
<?php include '../../common/view/footer.html.php';?>
