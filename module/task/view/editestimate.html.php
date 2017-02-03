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
<div class='container mw-600px'>
  <div id='titlebar'>
    <div class='heading'>
      <strong><?php echo $lang->task->editEstimate;?></strong>
      <small class='text-muted'><?php echo html::icon($lang->icons['edit']);?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' <?php if($estimate->isLast) echo "onsubmit='return confirmLeft();'"?>>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->task->date;?></th>
        <td class='w-p45'><?php echo html::input('date', $estimate->date, 'class="form-control form-date"');?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->task->record;?></th>
        <td><?php echo html::input('consumed', $estimate->consumed, 'class="form-control" autocomplete="off"');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->task->left;?></th>
        <td><?php echo html::input('left', $estimate->left, 'class="form-control" autocomplete="off"');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->comment;?></th>
        <td colspan='2'><?php echo html::textarea('work', $estimate->work, "class=form-control");?></td>
      </tr>  
      <tr>
        <td></td><td colspan='2' class='text-center'>
          <?php echo html::submitButton() . html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
