window.renderRowData = function($row, index, row)
{
    if(row.branchID ==  main_branch)
    {
        $row.find('.form-batch-input[data-name="branchID"]').attr('disabled', 'disabled');
        $row.find('.form-batch-input[data-name="name"]').attr('disabled', 'disabled');
        $row.find('.form-batch-input[data-name="desc"]').attr('disabled', 'disabled');

        $row.find('[id^="status"]').on('inited', function(e, info)
        {
            info[0].render({disabled: true});
        });
    }
}
