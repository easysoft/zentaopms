$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('storyIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

/**
 * 对标题列进行重定义。
 * Redefine the title column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, info)
{
    if(info.col.name == 'title' && result[0])
    {
        const story = info.row.data;
        if(story.parent)
        {
            let html = "<span class='label gray-pale rounded-xl' title='" + children + "'>" + childrenAB + "</span>";
            result.unshift({html});
        }
    }
    return result;
}
