<style>
#successModal #modalLink{color: #2e7fff;text-decoration: underline;}
#successModal .modal-dialog .modal-content{border: 2px solid #75e5c4; background-color: #f1fcf9; border-radius: 4px;}
#successModal .icon-check-circle{color: #17ce97; margin-right: 10px; font-size: 20px;}
#successModal .modal-dialog{width: fit-content;}
#successModal .modal-dialog{border-radius: 4px;}
#successModal .modal-header{padding:9px 15px 0px; margin:0px; border-bottom:0;}
#successModal .modal-dialog .modal-body{padding: 5px 15px 20px 15px;}
</style>
<div class="modal fade" id="successModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <?php echo html::a($closeLink, '×', '', 'class="close"');?>
      </div>
      <div class="modal-body">
        <p>
          <i class="icon-check-circle icon"></i><?php echo $notice;?>
          <?php $modalLink = !empty($modalLink) ? $modalLink : '';?>
          <?php echo html::a($modalLink, $buttonName, '', 'id="modalLink"');?>
        </p>
      </div>
    </div>
  </div>
</div>
<script>
function showModal(url = '')
{
    if(url) $("#modalLink").attr('href', url);
    $('#successModal').modal('show', 'center');
}
</script>
