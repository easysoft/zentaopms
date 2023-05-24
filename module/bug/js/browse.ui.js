window.batchEditBugs = function(event)
{
    const dtable = zui.DTable.query(event.target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const form = new FormData();
    const url  = $(event.target).closest('.btn').data('url');
    checkedList.forEach((id) => form.append('bugIdList[]', id));
    postAndLoadPage(url, form);
}
