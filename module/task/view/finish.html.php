<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?> <strong><?php echo $task->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name, '_blank');?></strong>
    <small class='text-success'> <?php echo $lang->task->finish;?> <?php echo html::icon($lang->icons['finish']);?></small>
  </div>
</div>
<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->task->consumed;?></th>
      <td class='w-p25-f'><div class='input-group'><?php echo html::input('consumed', $task->consumed, "class='form-control'");?> <span class='input-group-addon'><?php echo $lang->task->hour;?></span></div></td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->task->assignedTo;?></th>
      <td><?php echo html::select('assignedTo', $members, $task->openedBy, "class='form-control chosen'");?></td><td></td>
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
      <th></th><td colspan='2'><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<div class='main'><?php include '../../common/view/action.html.php';?></div>
<?php include '../../common/view/footer.html.php';?>
