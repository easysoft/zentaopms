$(document).off('click', '.link-btn').on('click', '.link-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();

    if(!checkedList.length) return;

    checkedList.forEach((id) => {

        rowData = dtable.$.getRowInfo(id).data;
        const caseTitle =  '#' + id + rowData.title;

        $('#linkCase').append("<div class='checkbox-primary'><input type='checkbox' id='linkBug[]_" + id + "' name='linkBug[]' checked value=" + id + "><label for='linkBug[]_" + id + "'>" + caseTitle + "</label></div>");
        $('#linkCase').closest('tr').removeClass('hidden');
    });

    $('.modal').trigger('to-hide.modal.zui');
});
