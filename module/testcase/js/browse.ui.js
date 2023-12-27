$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();

    checkedList.forEach((id) => {
        const data = dtable.$.getRowInfo(id).data;
        if(data.isScene)  form.append('sceneIdList[]', data.caseID);
        if(!data.isScene) form.append('caseIdList[]',  data.caseID);
    });

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
window.toggleOnlyAutoCase = function(event)
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
    if(result)
    {
        if(col.name == 'caseID' && row.data.isScene)
        {
            result.shift(); // 移除场景ID
        }
        if(col.name == 'title')
        {
            const data = row.data;
            const module = this.options.customData.modules[data.module];
            if(data.color) result[0].props.style = 'color: ' + data.color;
            if(data.isScene) // 场景
            {
                result.shift(); // 移除带链接的场景名称
                result.push({html: data.title}); // 添加不带链接的场景名称
                if(data.grade == 1 && module) result.unshift({html: '<span class="label lighter rounded-full">' + module + '</span>'}); // 顶级场景添加模块标签
                result.unshift({html: '<span class="label gray-300-outline text-gray rounded-full">' + scene + '</span>'}); // 添加场景标签
                if(!this.options.customData.isOnlyScene && data.hasCase == false) result.push({html: '<span class="text-gray">(' + noCase + ')</span>'}); // 添加暂无用例标签
            }
            else // 用例
            {
                if(data.auto == 'auto') result.unshift({html: '<span class="label lighter rounded-full">' + automated + '</span>'}); // 添加自动化标签
                if(module) result.unshift({html: '<span class="label lighter rounded-full">' + module + '</span>'}); // 添加模块标签
            }
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
