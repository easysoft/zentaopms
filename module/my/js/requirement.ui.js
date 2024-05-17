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
 * 根据状态变更操作按钮的语言项。
 * Change recall btn lang of object status.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {row, col})
{
    if(col.name == 'actions')
    {
        for(index in row.data.actions)
        {
            if(row.data.actions[index].name == 'recall') row.data.actions[index].hint = (row.data.status == 'changing' ? recallChange : recall
        }
    }
    return result;
}
