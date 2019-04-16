<?php
/**
 * The editEstimate view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: editestimate.html.php 4263 2013-02-24 08:50:46Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('confirmRecord', $lang->task->confirmRecord);?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-600px'>
    <div class='main-header'>
      <h2><?php echo $lang->task->editEstimate;?></h2>
    </div>
    <form method='post' target='hiddenwin' <?php if($estimate->isLast) echo "onsubmit='return confirmLeft();'"?>>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->task->date;?></th>
          <td class='w-p45'><?php echo html::input('date', $estimate->date, 'class="form-control form-date"');?></td>
          <td></td>
        </tr>  
        <tr>
          <th><?php echo $lang->task->record;?></th>
          <td><?php echo html::input('consumed', $estimate->consumed, 'class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->left;?></th>
          <td><?php echo html::input('left', $estimate->left, 'class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('work', $estimate->work, "class=form-control");?></td>
        </tr>  
        <tr>
          <td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
