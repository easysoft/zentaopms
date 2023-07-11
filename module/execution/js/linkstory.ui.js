$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('stories[]', id));

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
 * 渲染分支字段。
 * Render branch field.
 *
 * @param  array  result
 * @param  object row
 * @param  object col
 * @access public
 * @return void
 */
window.onRenderLinkStoryCell = function(result, {row, col})
{
    if(col.name !== 'branch')
    {
        return result;
    }

    if(branchGroups[row.data.product] && branchGroups[row.data.product][row.data.branch])
    {
        return [branchGroups[row.data.product][row.data.branch]];
    }

    return [''];
};
