window.handleClickBatchFormAction = function(action, $row, rowIndex)
{
    if(action == 'delete')
    {
        let deleteID = $row.find('td[data-name="id"] input[name^=id]').val();
        if(deleteID)
        {
            deleteID = deleteID.replace('id', '');
            let link = $.createLink('doc', 'ajaxJudgeCanBeDeleted', 'scopeID=' + deleteID);
            $.getJSON(link, function(data)
            {
                if(data && data.result == 'fail')
                {
                    zui.Modal.alert(data.message);
                }
                else
                {
                    const $batchForm = $('[data-zui-batchform]').zui('batchForm');
                    $batchForm.deleteRow(rowIndex);
                }
            });
            return false;
        }
    }
}

window.handleRenderRow = function($row, index, data)
{
    if(data && data.main == '1') $row.find('[data-name="ACTIONS"]').find('[data-type="delete"]').addClass('hidden');
}
