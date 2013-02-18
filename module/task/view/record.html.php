<?php
/**
 * The record file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     task
 * @version     $Id: record.html.php 935 2013-01-08 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmFinish', $lang->task->confirmFinish);?>
<form method='post' target='hiddenwin' onsubmit='return checkLeft();'>
  <table class='table-1'>
    <caption><?php echo $task->name;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->task->beforeConsumed;?></th>
      <td><?php echo $task->consumed . ' ' . $lang->task->hour;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->consumedThisTime;?></th>
      <td><?php echo html::input('consumed', '', "class='text-2'") . $lang->task->hour;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->left;?></th>
      <td><?php echo html::input('left', '', "class='text-2'") . $lang->task->hour;?></td>
    </tr>
    <tr>
      <td class='rowhead'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "rows='6' class='area-1'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton(); ?></td>
    </tr>
  </table>
  <?php include '../../common/view/action.html.php';?>
</form>
<?php include '../../common/view/footer.html.php';?>
