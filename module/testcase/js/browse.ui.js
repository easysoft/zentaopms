$(document).off('click').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('caseIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data:form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

window.onRenderCell = function(result, {row, col})
{
    if(result && row.data.isCase == 1 && col.name == 'title')
    {
        const prefix = result.slice(0, - 2);
        const suffix = result.slice(-2);
        const first  = result.shift();
        const id     = row.data.id;
        const html   = `<span class='text-gray'>${id}</span>`;
        result = prefix.concat({html}, suffix);
    }
    return result;
}

/**
 * 计算表格信息的统计。
 * Set summary for table footer.
 *
 * @param  element element
 * @param  array   checks
 * @access public
 * @return object
 */
window.setStatistics = function(element, checks)
{
    if(checks.length)
    {
        runCaseCount = 0;
        checks.forEach((id) => {
            const scene = element.getRowInfo(id).data;
            if(scene.isCase == 1 && scene.lastRunResult != '') runCaseCount++;
        });
        return zui.formatString(checkedSummary, {
            checked: checks.length,
            run: runCaseCount
        });
    }

    return pageSummary;
}
