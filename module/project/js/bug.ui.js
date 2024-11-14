$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('bugIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data:form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

/**
 * 标题列显示额外的内容。
 * Display extra content in the title column.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'title' && row.data.color) result[0].props.style = 'color: ' + row.data.color;
    if(col.name == 'deadline' && result[0])
    {
        const bug = row.data;
        if(['resolved', 'closed'].includes(bug.status)) return result;

        const yesterday = zui.formatDate(zui.createDate() - 24 * 60 * 60 * 1000, 'yyyy-MM-dd');
        if(result[0] <= yesterday) result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + result[0] + '</span>'};
    }

    return result;
}
