$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
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
        if(task.hasChild) return false;
        if(task.status == 'wait')  waitCount ++;
        if(task.status == 'doing') doingCount ++;
        estimate += task.estimate;
        consumed += task.consumed;
        left     += task.left;
    })
    if(checks.length) return {html: element.options.checkedSummary.replaceAll('%total%', `${checks.length}`).replaceAll('%wait%', waitCount).replaceAll('%doing%', doingCount).replaceAll('%estimate%', estimate).replaceAll('%consumed%', consumed).replaceAll('%left%', left)};
    return zui.formatString(element.options.defaultSummary);
}

/**
 * 对部分列进行重定义。
 * Redefine the partial column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, info)
{
    if(info.col.name == 'name' && result)
    {
        const task = info.row.data;
        let html = '';
        if(task.team)
        {
            html += "<span class='label gray-pale rounded-xl'>" + multipleAB + "</span>";
        }
        if(task.parent > 0)
        {
            html += "<span class='label gray-pale rounded-xl'>" + childrenAB + "</span>";
        }
        if(html) result.unshift({html});
    }
    if(info.col.name == 'deadline' && result[0])
    {
        const today     = zui.formatDate(zui.createDate(), 'yyyy-MM-dd');
        const yesterday = zui.formatDate(convertStringToDate(today) - 24 * 60 * 60 * 1000, 'yyyy-MM-dd');
        if(result[0] == today)
        {
            result[0] = {html: '<span class="label warning-pale rounded-full size-sm">' + todayLabel + '</span>'};
        }
        else if(result[0] == yesterday)
        {
            result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + yesterdayLabel + '</span>'};
        }
        else if(result[0] < yesterday)
        {
            result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + result[0] + '</span>'};
        }
    }
    return result;
}

function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];

    return Date.parse(dateString);
}
