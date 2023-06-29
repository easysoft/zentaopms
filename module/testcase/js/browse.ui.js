$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
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

/**
 * 切换显示所有用例和自动化用例。
 * Toggles between displaying all cases and automation cases.
 *
 * @param  event $event
 * @access public
 * @return void
 */
function toggleOnlyAutoCase(event)
{
    const onlyAutoCase = $(event.target).prop('checked') ? 1 : 0;
    $.cookie.set('onlyAutoCase', onlyAutoCase, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
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
        const data = row.data;
        if(data.isCase == 1 && data.auto == 'auto')
        {
            result.unshift({html: '<span class="label lighter rounded-full">' + automated + '</span>'}); // 添加自动化标签
        }
        else if(data.isCase == 2)
        {
            result.pop(); // 移除带链接的场景名称
            result.push({html: data.title}); // 添加不带链接的场景名称
            if(data.parent > 0) result.unshift({html: '<span class="label lighter rounded-full">' + children + '</span>'}); // 添加子场景标签
            result.unshift({html: '<span class="label light-outline text-gray rounded-full">' + scene + '</span>'}); // 添加场景标签
            if(!this.options.customData.isOnlyScene && this.options.customData.caseScenes[data.id] === undefined) result.push({html: '<span class="text-gray">(' + noCase + ')</span>'}); // 添加暂无用例标签
        }
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

    return element.options.customData.pageSummary;
}

/**
 * Get selected case id list.
 *
 * @access public
 * @return void
 */
function getCheckedCaseIdList()
{
    let caseIdList = '';

    const dtable = zui.DTable.query('#table-testcase-browse');
    $.each(dtable.$.getChecks(), function(index, caseID)
    {
        if(index > 0) caseIdList += ',';
        caseIdList += caseID;
    });
    $('#caseIdList').val(caseIdList);
}
