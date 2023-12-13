window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return "javascript:loadModal('" + sortLink.replace('{orderBy}', sort) + "', '#table-repo-linkbug')";
}

$(document).off('click','.dtable-footer .batch-btn-repo').on('click', '.dtable-footer .batch-btn-repo', function(e)
{
    const dtable = zui.DTable.query(e.target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const tabType  = $(this).data('type');
    const postData = new FormData();
    checkedList.forEach((id) => postData.append(`${tabType}[]`, id));

    $.ajaxSubmit({
        url:  $(this).data('url'),
        data: postData
    });
});
