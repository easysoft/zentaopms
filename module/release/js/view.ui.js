$(document).off('click','.dtable-footer .batch-btn').on('click', '.dtable-footer .batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = [];
    postData[`${type}IdList[]`] = checkedList;

    $.ajaxSubmit({
        url:  $(this).data('url'),
        data: postData
    });
});
