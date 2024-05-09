$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();

    checkedList.forEach((id) => {form.append('caseIdList[]', id);});

    if($(this).hasClass('ajax-btn'))
    {
        if($(this).hasClass('batch-delete-btn'))
        {
            zui.Modal.confirm(confirmBatchDelete).then((res) => {if(res) $.ajaxSubmit({url, data:form});});
        }
        else
        {
            $.ajaxSubmit({url, data:form});
        }
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
    if(result)
    {
        if(col.name == 'title')
        {
            const data   = row.data;
            const module = this.options.customData.modules[data.module];
            if(data.color) result[0].props.style = 'color: ' + data.color;
            if(module) result.unshift({html: '<span class="label gray-pale rounded-full">' + module + '</span>'}); // 添加模块标签
        }
    }

    return result;
}
