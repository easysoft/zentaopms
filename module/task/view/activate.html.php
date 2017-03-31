<?php
/**
 * The activate file of task module of ZenTaoPMS.
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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?> <strong><?php echo $task->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name, '_blank');?></strong>
    <small class='text-success'> <?php echo $lang->task->activate;?> <?php echo html::icon($lang->icons['activate']);?></small>
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->task->assignedTo;?></th>
      <td class='w-p25-f'><?php echo html::select('assignedTo', $members, $task->finishedBy, "class='form-control chosen'");?></td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->task->left;?></th>
      <td>
        <div class='input-group'>
          <?php echo html::input('left', '', "class='form-control' autocomplete='off'");?>
          <span class='input-group-addon'><?php echo $lang->task->hour;?></span>
        </div>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->comment;?></th>
      <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='w-p98'");?></td>
    </tr>
    <tr>
      <td colspan='3' class='text-center'>
       <?php 
       echo html::submitButton($lang->task->activate);
       echo html::linkButton($lang->goback, $this->session->taskList);
       ?>
      </td>
    </tr>
  </table>
</form>
<div class='main'>
  <?php include '../../common/view/action.html.php';?>
</div>
<?php include '../../common/view/footer.html.php';?>
