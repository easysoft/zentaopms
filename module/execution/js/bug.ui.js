$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable      = zui.DTable.query($(this).target);
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
 * 计算表格Bug信息的统计。
 * Set bug summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIDList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checkedIDList)
{
    let totalCount = 0;

    const rows  = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIDList.length == 0 || checkedIDList.includes(row.id))
        {
            totalCount ++;
        }
    })

    const summary = checkedIDList.length > 0 ? checkedSummary.replace('{0}', totalCount) : element.props.summary;
    return {html: summary};
}

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
        if(module) result.unshift({html: '<span class="label gray-pale rounded-full nowrap">' + module + '</span>'}); // 添加模块标签

        if(parseInt(row.data.case))
        {
            const caseLink = $.createLink('testcase', 'view', "caseID=" + row.data.case + "&version=" + row.data.caseVersion);
            result.push({html: '<a href="' + caseLink + '"class="text-gray" title="' + row.data.case + '">[' + caseCommonLang + '#' + row.data.case + ']</a>'});
        }
    }
    if(col.name == 'deadline' && result[0])
    {
        const bug = row.data;
        if(['resolved', 'closed'].includes(bug.status)) return result;

        const yesterday = zui.formatDate(zui.createDate() - 24 * 60 * 60 * 1000, 'yyyy-MM-dd');
        if(result[0] <= yesterday) result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + result[0] + '</span>'};
    }

    return result;
}
