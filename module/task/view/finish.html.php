<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
<div class='yui-d0'>
  <table class='table-1'>
    <caption><?php echo $task->name;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->task->consumed;?></th>
      <td><?php echo html::input('consumed', $task->consumed, "class='text-3'");?></td>
    </tr>  
    <tr>
      <td class='rowhead'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "rows='6' class='area-1'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->taskList);?></td>
    </tr>
  </table>
  <?php include '../../common/view/action.html.php';?>
</div>

<?php include '../../common/view/footer.html.php';?>
