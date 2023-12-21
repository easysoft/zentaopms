$(document).off('click', '.link-btn').on('click', '.link-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();

    if(!checkedList.length) return;

    checkedList.forEach((id) => {

        rowData = dtable.$.getRowInfo(id).data;
        const caseTitle =  '#' + id + rowData.title;

        $('#linkCase').append("<div class='checkbox-primary'><input type='checkbox' id='linkCase[]_" + id + "' name='linkCase[]' checked value=" + id + "><label for='linkCase[]_" + id + "'>" + caseTitle + "</label></div>");
        $('#linkCase').closest('tr').removeClass('hidden');
    });

    $(this).closest('.modal').modal('hide');
});
