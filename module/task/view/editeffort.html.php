<?php
/**
 * The editEffort view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: editestimate.html.php 4263 2013-02-24 08:50:46Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('confirmRecord', $lang->task->confirmRecord);?>
<?php js::set('today', helper::today());?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-600px'>
    <div class='main-header'>
      <h2><?php echo $lang->task->editEffort;?></h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->task->date;?></th>
          <td class='w-p45 required'><?php echo html::input('date', $estimate->date, 'class="form-control form-date"');?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->record;?></th>
          <td class='required'><?php echo html::input('consumed', $estimate->consumed, 'class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->task->left;?></th>
          <?php $readonly = $estimate->isLast ? '' : 'readonly';?>
          <?php if(!empty($task->team) and $estimate->left == 0) $readonly = 'readonly';?>
          <td><?php echo html::input('left', $estimate->left, "class='form-control' {$readonly}");?></td>
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
<?php if($estimate->isLast):?>
<script>
$(function()
{
    $("#submit").click(function(e, confirmed)
    {
        if(confirmed) return true;

        var $this = $(this);
        var $left = $('#left');
        var left  = $.trim($left.val());
        if(!$left.prop('readonly') && left == 0)
        {
            e.preventDefault();
            bootbox.confirm(confirmRecord, function(result)
            {
                if(result) $this.trigger('click', true);
            });
        }
    });
})
</script>
<?php endif;?>
<?php include '../../common/view/footer.lite.html.php';?>
