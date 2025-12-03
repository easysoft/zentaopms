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
}).off('click', '#actionBar .export-btn').on('click', '#actionBar export-btn', function()
{
    const dtable = zui.DTable.query($('#table-bug-browse'));
    const checkedList = dtable ? dtable.$.getChecks() : [];
    if(!checkedList.length) return;

    $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});
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
    if(from == 'doc' || from == 'ai') return result;

    if(result && col.name == 'title')
    {
        result[0].props.className = 'overflow-hidden';
        if(row.data.color) result[0].props.style = 'color: ' + row.data.color;
        const module = this.options.modules[row.data.module];
        if(module) result.unshift({html: '<span class="label gray-pale rounded-full whitespace-nowrap w-auto">' + module + '</span>'}); // 添加模块标签

        if(parseInt(row.data.case))
        {
            caseLink = $.createLink('testcase', 'view', "caseID=" + row.data.case + "&version=" + row.data.caseVersion);
            result.push({html: '<a href="' + caseLink + '"class="text-gray" title="' + row.data.case + '">[' + caseCommonLang + '#' + row.data.case + ']</a>'});
        }
    }

    if(result[0] && col.name == 'deadline')
    {
        const bug = row.data;
        if(['resolved', 'closed'].includes(bug.status)) return result;

        const yesterday = zui.formatDate(zui.createDate() - 24 * 60 * 60 * 1000, 'yyyy-MM-dd');
        if(result[0] <= yesterday) result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + result[0] + '</span>'};
    }

    return result;
}

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#bugs'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
