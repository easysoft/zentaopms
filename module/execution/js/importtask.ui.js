$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const form = new FormData();
    checkedList.forEach((id) => form.append('tasks[]', id));

    postAndLoadPage($(this).data('url'), form);
});
