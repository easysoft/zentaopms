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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><i class="icon-file-text"></i> <?php echo $lang->todo->assignTo;?></h4>
      </div>
      <div class="modal-body">
        <form class='form-condensed' method='post' target='hiddenwin' id="todoAssignForm">
          <table class='table table-form'>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->assignTo;?></th>
              <td class='w-p25-f'>
                  <?php echo html::select('assignedTo', $members, '', "class='form-control chosen'");?>
              </td><td></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->date;?></th>
              <td>
                <div class='input-group'>
                    <?php echo html::input('date', $date, "class='form-control form-date' id='todoDate'");?>
                  <span class='input-group-addon'><input type='checkbox' name="future" id='switchDate' onclick='switchDateTodo(this);'> <?php echo $lang->todo->periods['future'];?></span>
                </div>
              </td><td></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->beginAndEnd;?></th>
              <td>
                <div class='input-group'>
                    <?php echo html::select('begin', $times, date('Y-m-d') != $date ? key($times) : $time, 'onchange=selectNext(); class="form-control chosen" style="width: 50%;"') . html::select('end', $times, '', 'class="form-control chosen" style="width: 50%; margin-left:-1px"');?>
                </div>
              </td>
              <td><input type='checkbox' id='switchDate' onclick='switchDateFeature(this);' name="lblDisableDate"> <?php echo $lang->todo->lblDisableDate;?></td>
            </tr>
            <tfoot>
            <tr><td colspan='3'><?php echo html::submitButton();?></td></tr>
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