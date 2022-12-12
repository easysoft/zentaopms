<div class="modal fade" id="successModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <?php echo html::a($this->createLink('zanode', 'browse'), '×', '', 'class="close"');?>
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
