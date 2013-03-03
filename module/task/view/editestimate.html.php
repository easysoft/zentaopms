<?php
/**
 * The editEstimate view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: editestimate.html.php 4263 2013-02-24 08:50:46Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1 a-left'> 
    <caption><?php echo $lang->task->editEstimate;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->task->date;?></th>
      <td><?php echo html::input('date', $estimate->date, 'class="select-3 date"');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->record;?></th>
      <td><?php echo html::input('consumed', $estimate->consumed, 'class="select-3"');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->left;?></th>
      <td><?php echo html::input('left', $estimate->left, 'class="select-3"');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->comment;?></th>
      <td><?php echo html::textarea('work', $estimate->work, "class=text-5");?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'>
        <?php echo html::submitButton() . html::resetButton();?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
