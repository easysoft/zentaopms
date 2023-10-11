$(document).off('click', '.batch-btn > a, .batch-btn').on('click', '.batch-btn > a, .batch-btn', function()
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
    if(result && col.name == 'title')
    {
        const module = this.options.modules[row.data.module];
        if(module) result.unshift({html: '<span class="label lighter rounded-full">' + module + '</span>'}); // 添加模块标签
    }

    return result;
}
