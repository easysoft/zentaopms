/**
 * 状态列渲染，对changed/casechanged 状态的标红处理。
 * Render status cell with red styling for changed/casechanged status.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'status')
    {
        if(row.data.status == 'casechanged' || row.data.status == 'changed')
        {
            var changedText = row.data.status == 'casechanged' ? caseChanged : storyChanged;
            result[0] = {html: '<span style="color:#ff6f42">' + changedText + '</span>'};
        }
    }
    return result;
}

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
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

/**
 * 设置表格的统计信息。
 * Set summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIDList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checks)
{
    let failCount  = 0;
    checks.forEach((checkID) => {
        const caseInfo = element.getRowInfo(checkID).data;
        if(caseInfo.lastRunResult != unexecuted && caseInfo.lastRunResult != 'pass')  failCount ++;
    })
    if(checks.length) return {html: element.options.checkedSummary.replaceAll('%total%', `${checks.length}`).replaceAll('%fail%', failCount)};
    return zui.formatString(element.options.defaultSummary);
}
