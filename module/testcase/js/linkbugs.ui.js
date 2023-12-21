$(document).off('click', '.link-btn').on('click', '.link-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();

    if(!checkedList.length) return;

    checkedList.forEach((id) => {

        rowData = dtable.$.getRowInfo(id).data;
        const bugTitle =  '#' + id + rowData.title;

        $('#linkBug').append("<div class='checkbox-primary'><input type='checkbox' id='linkBug[]_" + id + "' name='linkBug[]' checked value=" + id + "><label for='linkBug[]_" + id + "'>" + bugTitle + "</label></div>");

        $('#linkBug').closest('tr').removeClass('hidden');
    });

    $(this).closest('.modal').modal('hide');
});
