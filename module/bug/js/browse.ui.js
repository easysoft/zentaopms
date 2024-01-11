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
    if(result && col.name == 'title')
    {
        if(row.data.color) result[0].props.style = 'color: ' + row.data.color;
        const module = this.options.modules[row.data.module];
        if(module) result.unshift({html: '<span class="label lighter rounded-full">' + module + '</span>'}); // 添加模块标签

        if(row.data.case)
        {
            caseLink = $.createLink('testcase', 'view', "caseID=" + row.data.case + "&version=" + row.data.caseVersion);
            result.push({html: '<a href="' + caseLink + '"class="text-gray" title="' + row.data.case + '">[' + caseCommonLang + '#' + row.data.case + ']</a>'});

        }
    }

    return result;
}
