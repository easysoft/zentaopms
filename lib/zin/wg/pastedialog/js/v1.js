function importLines(target, field)
{
    const $modal  = $(target).closest('.modal');
    const $dialog = $modal.find('.modal-dialog');
    const $lines  = $modal.find('textarea');
    const $form   = $modal.closest('.container').find('form.form-batch');
    const modalID = $modal.attr('id');

    $dialog.addClass('loading');

    setTimeout(function()
    {
        let $currentRow;

        const lines = $lines.val().split('\n');
        $.each(lines, function(index, line)
        {
            line = line.trim();
            if(!line.length) return true;

            if($currentRow)
            {
                $row = $currentRow.next();
            }
            else
            {
                $row = $form.find('tbody>tr [name^=' + field + ']').first().closest('tr');
            }

            while($row.length && $row.find('[name^=' + field + ']').val().length)
            {
                $row = $row.next();
            }

            if(!$row || !$row.length)
            {
                $form.data('zui.BatchForm').addRow();
                $row = $form.find('tbody>tr [name^=' + field + ']').last().closest('tr');
            }

            $row.find('[name^=' + field + ']').val(line).addClass('highlight');

            $currentRow = $row;
        });

        $lines.val('');
        $dialog.removeClass('loading');

        $modal.on('hidden.Modal.zui', function()
        {
            const $firstRow = $form.find('tbody>tr [name^=' + field + ']').first().closest('tr');
            $firstRow[0].scrollIntoView();
        });
        zui.Modal.hide('#' + modalID);

        setTimeout(function()
        {
            $form.find('[name^=' + field + '].highlight').removeClass('highlight');
        }, 3000);
    }, 200);
};
