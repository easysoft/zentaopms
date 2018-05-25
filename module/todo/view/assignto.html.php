<?php
/**
 * The batch create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     todo
 * @version     $Id: create.html.php 2741 2012-04-07 07:24:21 $
 * @link        http://www.zentao.net
 */
?>
<div class="modal fade" id="assigntoModal">
  <div class="modal-dialog mw-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->todo->assignTo;?></h4>
      </div>
      <div class="modal-body">
        <form class='load-indicator main-form' method='post' target='hiddenwin' id="todoAssignForm">
          <table class='table table-form'>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->assignTo;?></th>
              <td>
                  <?php echo html::select('assignedTo', $members, '', "class='form-control chosen'");?>
              </td><td></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->date;?></th>
              <td><?php echo html::input('date', $date, "class='form-control form-date' id='todoDate'");?></td>
              <td>
                <div class='checkbox-primary'>
                  <input type='checkbox' name="future" id='switchDate' onclick='switchDateTodo(this);' />
                  <label for='switchDate'><?php echo $lang->todo->periods['future'];?></label>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->beginAndEnd;?></th>
              <td>
                <div class='w-p50 pull-left'>
                  <?php echo html::select('begin', $times, date('Y-m-d') != $date ? key($times) : $time, 'onchange=selectNext(); class="form-control chosen"');?>
                </div>
                <div class='w-p50 pull-left'>
                  <?php echo html::select('end', $times, '', 'class="form-control chosen" margin-left:-1px"');?>
                </div>
              </td>
              <td>
                <div class='checkbox-primary'>
                  <input type='checkbox' id='switchDate' onclick='switchDateFeature(this);' name="lblDisableDate">
                  <label for='switchDate'><?php echo $lang->todo->lblDisableDate;?></label>
                </div>
              </td>
            </tr>
            <tfoot>
            <tr><td colspan='3' class='text-center form-actions'><?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?></td></tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $("a[data-toggle='assigntoModal']").on('click', function(){
      $('#assigntoModal').modal('show');
      $('#todoAssignForm').attr('action', $(this).attr('href'));
      return false;
  });
  function switchDateTodo(switcher)
  {
      if(switcher.checked)
      {
          $('#todoDate').attr('disabled','disabled');
      }
      else
      {
          $('#todoDate').removeAttr('disabled');
      }
  }

  function switchDateFeature(switcher)
  {
      if(switcher.checked)
      {
          $('#begin').attr('disabled','disabled').trigger('chosen:updated');
          $('#end').attr('disabled','disabled').trigger('chosen:updated');
      }
      else
      {
          $('#begin').removeAttr('disabled').trigger('chosen:updated');
          $('#end').removeAttr('disabled').trigger('chosen:updated');
      }
  }
</script>
<style>#end_chosen .chosen-single{border-left: none;}</style>
