$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const form = new FormData();
    checkedList.forEach((id) => form.append('taskIdList[]', id));

    $.ajaxSubmit({url: $(this).data('url'), data: form});
});

window.changeExecution = function(e)
{
    loadPage($.createLink('execution', 'importTask', 'executionID=' + executionID + '&fromExecution=' + $(e.target).val()));
}

window.renderCell = function(result, info)
{
    const task = info.row.data;
    if(info.col.name == 'name' && result)
    {
        let html = '';

        if(task.isParent > 0)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + parentAB + "</span>";
        }
        else if(task.parent > 0)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + childrenAB + "</span>";
        }

        if(html) result.unshift({html});
    }

    return result;
}

$(document).off('click', '.switchButton').on('click', '.switchButton', function()
{
    var taskViewType = $(this).attr('data-type');
    $.cookie.set('taskViewType', taskViewType, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});
