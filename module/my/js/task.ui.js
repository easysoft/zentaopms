$(document).on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('taskIdList[]', id));

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
 * 计算表格任务信息的统计。
 * Set todo summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIDList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checks)
{
    let waitCount  = 0;
    let doingCount = 0;
    let estimate   = 0;
    let consumed   = 0;
    let left       = 0;
    checks.forEach((checkID) => {
        const task = element.getRowInfo(checkID).data;
        if(task.status == 'wait')  waitCount ++;
        if(task.status == 'doing') doingCount ++;
        estimate += task.estimate;
        consumed += task.consumed;
        left     += task.left;
    })
    if(checks.length) return {html: element.options.checkedSummary.replaceAll('%total%', `${checks.length}`).replaceAll('%wait%', waitCount).replaceAll('%doing%', doingCount).replaceAll('%estimate%', estimate).replaceAll('%consumed%', consumed).replaceAll('%left%', left)};
    return zui.formatString(element.options.defaultSummary);
}
