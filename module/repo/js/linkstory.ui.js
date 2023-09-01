window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return "javascript:loadModal('" + sortLink.replace('{orderBy}', sort) + "', '#table-repo-linkstory')";
}

$(document).off('click','.dtable-footer .batch-btn').on('click', '.dtable-footer .batch-btn', function(e)
{
    const dtable = zui.DTable.query(e.target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const tabType  = $(this).data('type');
    const postData = [];
    postData[`${tabType}[]`] = checkedList;

    $.ajaxSubmit({
        url:  $(this).data('url'),
        data: postData
    });
});
