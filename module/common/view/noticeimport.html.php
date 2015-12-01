<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
        <?php echo html::hidden('insert', '');?>
        <div class='alert alert-info'><?php echo $lang->noticeImport;?></div>
      </div>
    </div>
  </div>
</div>
<script>
$(function(){$("button[data-toggle='myModal']").click(function(){$('#myModal').modal('show')})});
function submitForm(type)
{
    if(type == 'insert')
    {
        $('#insert').val('1');
    }
    else
    {
        $('#insert').val('0');
    }
    $("button[data-toggle='myModal']").closest('form').submit();
}
</script>
