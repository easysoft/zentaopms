<div id="importLinesModal" class="modal fade">
  <div class="modal-dialog modal-lg modal-simple load-indicator">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->pasteText;?></h4>
      </div>
      <div class="modal-body">
    	<?php echo html::textarea('importLines', '', "class='form-control mgb-10' rows='10' placeholder='$lang->pasteTextInfo'")?>
      </div>
      <div class="modal-footer text-left">
        <button type="button" class="btn btn-primary btn-wide" id="importLinesBtn"><?php echo $lang->save;?></button>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    $("button[data-toggle='importLinesModal']").click(function(){$('#importLinesModal').modal('show')})
    $form = $('#batchCreateForm');
    var batchForm = $form.data('zui.batchActionForm');
    
    var $importLines = $('#importLines');
    $('#importLinesBtn').on('click', function()
    {
        var $modal = $('#importLinesModal');
        var $dialog = $modal.find('.modal-dialog').addClass('loading');
        setTimeout(function()
        {
            var importText = $importLines.val();
            var lines = importText.split('\n');
            var $lastRow;
            $.each(lines, function(index, line)
            {
                line = $.trim(line);
                if (!line.length) return;
                if (!$lastRow) $row = $form.find('tbody>tr:first');
                else $row = $lastRow.next();
                while ($row.length && $row.find('.input-story-title').val().length)
                {
                    $row = $row.next();
                }
                if (!$row || !$row.length)
                {
                    $row = batchForm.createRow();
                }
                $row.find('.input-story-title').val(line);
                $lastRow = $row;
            });
            $importLines.val('');
            $dialog.removeClass('loading');
            $modal.modal('hide');
        }, 200);
    });
    
    $importLines.on('scroll', function()
    {
        $importLines.css('background-position-y', -$importLines.scrollTop() + 6);
    });
});
</script>
