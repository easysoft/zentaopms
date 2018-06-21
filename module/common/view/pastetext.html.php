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
    var $form = $('#batchCreateForm');
    var batchForm = $form.data('zui.batchActionForm');

    var rowTpl, $formTbody;
    var createRow = function()
    {
        if(!rowTpl) rowTpl = $('#trTemp tbody').html();
        if(!$formTbody) $formTbody = $form.find('table > tbody');
        var lastIndex = parseInt($formTbody.find('tr:last > td:first').text());
        var $newRow = $(rowTpl.replace(/%s/g, lastIndex + 1));
        $newRow.find('.chosen').chosen();
        $newRow.find('[data-provide="colorpicker-later"]').colorPicker();
        $formTbody.append($newRow);
        return $newRow;
    };
    
    var $importLines = $('#importLines');
    $('#importLinesBtn').on('click', function()
    {
        var $modal = $('#importLinesModal');
        var $dialog = $modal.find('.modal-dialog').addClass('loading');

        setTimeout(function()
        {
            var importText = $importLines.val();
            var lines = importText.split('\n');
            var $lastRow, $firstRow;
            $.each(lines, function(index, line)
            {
                line = $.trim(line);
                if (!line.length) return;
                if (!$lastRow) $row = $form.find('tbody>tr:first');
                else $row = $lastRow.next();
                while ($row.length && $row.find('.title-import').val().length)
                {
                    $row = $row.next();
                }
                if (!$row || !$row.length)
                {
                    if (batchForm) $row = batchForm.createRow();
                    else $row = createRow();
                }
                $row.find('.title-import').val(line).addClass('highlight');
                $lastRow = $row;
                if(!$firstRow) $firstRow = $row;
            });
            $importLines.val('');
            $dialog.removeClass('loading');
            $modal.on('hidden.zui.modal', function()
            {
                $firstRow[0].scrollIntoView();
            }).modal('hide');
            setTimeout(function()
            {
                $form.find('.title-import.highlight').removeClass('highlight');
            }, 3000);
        }, 200);
    });
    
    $importLines.on('scroll', function()
    {
        $importLines.css('background-position-y', -$importLines.scrollTop() + 6);
    });
});
</script>
