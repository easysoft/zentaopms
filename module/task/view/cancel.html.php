<?php
/**
 * The cancel file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: cancel.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?> <strong><?php echo $task->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name, '_blank');?></strong>
    <small class='text-muted'> <?php echo $lang->task->cancel;?> <?php echo html::icon($lang->icons['cancel']);?></small>
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin'>
  <table class='table table-form'>
    <tr>
      <th><?php echo $lang->comment;?></th>
      <td><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
    </tr>
    <tr><th></th><td><?php echo html::submitButton($lang->task->cancel);?></td></tr>
  </table>
</form>
<div class='main'>
  <?php include '../../common/view/action.html.php';?>
</div>
<?php include '../../common/view/footer.html.php';?>
