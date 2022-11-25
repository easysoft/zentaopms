<div class="modal fade" id="importNoticeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->importConfirm;?></h4>
      </div>
      <div class="modal-body">
        <?php echo html::hidden('insert', 0);?>
        <div class='alert with-icon'>
          <i class="icon icon-exclamation-sign"></i>
          <div class="content">
            <?php echo $lang->noticeImport;?>
          </div>
        </div>
        <div class="text-center form-actions">
          <a href='javascript:submitForm("cover")' class='btn btn-danger btn-wide'><?php echo $lang->importAndCover;?></a>
          <a href='javascript:submitForm("insert")' class='btn btn-primary btn-wide'><?php echo $lang->importAndInsert;?></a>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
#importNoticeModal .modal-dialog {top: 15% !important; max-height: 450px; overflow-y: auto;}
</style>
<script>
function submitForm(type)
{
    $('.modal-body #insert').val(type == 'insert' ? 1 : 0);
    $('#importNoticeModal .form-actions .btn').addClass('disabled');
    $("button[data-target='#importNoticeModal']").closest('form').submit();
}

$('#importNoticeModal .close').click(function()
{
    $('#importNoticeModal .form-actions .btn').removeClass('disabled');
})
</script>
