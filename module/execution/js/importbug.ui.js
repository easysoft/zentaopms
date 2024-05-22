$(document).off('click', '.import-bug-btn').on('click', '.import-bug-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return false;

    const importDTable = $('#table-execution-importbug').zui('dtable');
    const formData     = importDTable.$.getFormData();
    checkedList.forEach((id) => formData[`id[${id}]`] = id);

    $.ajaxSubmit({url: $('#importForm').attr('action'), data: formData, onFail: printError});

    return false;
});

/**
 * Print error message.
 *
 * @param  error  $error
 * @access public
 * @return void
 */
function printError(result)
{
    Object.entries(result.message).forEach(([name, msg]) => {
        if (Array.isArray(msg)) {
            msg = msg.join('');
        }
        zui.Modal.alert(msg);
    })
}
