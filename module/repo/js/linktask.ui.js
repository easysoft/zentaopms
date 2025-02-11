window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return "javascript:loadModal('" + sortLink.replace('{orderBy}', sort) + "', '#table-repo-linktask')";
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

window.renderTaskCell = function(result, info)
{
    const task = info.row.data;
    if(info.col.name == 'name' && result)
    {
        let html = '';
        if(task.mode)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + multipleAB + "</span>";
        }

        if(task.isParent > 0)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + parentAB + "</span>";
        }
        else if(task.parent > 0)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + childrenAB + "</span>";
        }

        if(task.color) result[0].props.style = 'color: ' + task.color;
        if(html) result.unshift({html});
    }

    return result;
}
