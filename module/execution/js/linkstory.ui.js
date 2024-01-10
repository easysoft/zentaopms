$(document).off('click','.batch-btn').on('click', '.batch-btn', function(e)
{
    const $modal = $(e.target).closest('.modal');
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('stories[]', id));

    headers = {};
    if($modal.length > 0) headers = {'X-Zui-Modal': true};
    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url: url, data: form, headers: headers});
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

window.onSearchLinks = function(type, result)
{
    loadComponent('#table-execution-linkstory', {url: result.load, component: 'dtable', partial: true});
};
