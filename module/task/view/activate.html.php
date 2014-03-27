<?php
/**
 * The activate file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: start.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form class='form-condensed' method='post' target='hiddenwin'>
  <table class='table table-form'>
    <caption><?php echo $task->name;?></caption>
    <tr>
      <th><?php echo $lang->task->assignedTo;?></th>
      <td><?php echo html::select('assignedTo', $members, $task->finishedBy, "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->task->left;?></th>
      <td><?php echo html::input('left', '', "class='form-control'") . $lang->task->hour;?></td>
    </tr>
    <tr>
      <td><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "rows='6' class='w-p98'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center'>
       <?php 
       echo html::submitButton();
       echo html::linkButton($lang->goback, $this->session->taskList);
       ?>
      </td>
    </tr>
  </table>
  <?php include '../../common/view/action.html.php';?>
</form>
<?php include '../../common/view/footer.html.php';?>
