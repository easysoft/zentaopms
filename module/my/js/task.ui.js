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
        if(task.rawStatus == 'wait')  waitCount ++;
        if(task.rawStatus == 'doing') doingCount ++;

        if(task.isParent > 0) return false;

        estimate += parseFloat(task.estimate);
        consumed += parseFloat(task.consumed);
        left     += parseFloat(task.left);
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
    const task = info.row.data;
    if(info.col.name == 'name' && result)
    {
        if(typeof result[0] == 'object') result[0].props.className = 'overflow-hidden';
        let html = '';
        if(task.team)
        {
            html += "<span class='label gray-pale rounded-xl nowrap'>" + multipleAB + "</span>";
        }
        if(task.isParent > 0)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + parentAB + "</span>";
        }
        else if(task.parent > 0)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + childrenAB + "</span>";
        }

        if(html) result.unshift({html});

        if(typeof task.delay != 'undefined' && task.delay && !['done', 'cancel', 'close'].includes(task.rawStatus))
        {
            result[result.length] = {html:'<span class="label danger-pale ml-1 flex-none nowrap">' + delayWarning.replace('%s', task.delay) + '</span>', className:'flex items-end', style:{flexDirection:"column"}};
        }
    }
    if(info.col.name == 'deadline' && result[0])
    {
        if(['done', 'cancel', 'close'].includes(task.rawStatus)) return result;

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
    if(info.col.name == 'status' && result)
    {
        result[0] = {html: `<span class='status-${info.row.data.rawStatus}'>` + info.row.data.status + "</span>"};
    }
    return result;
}

function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];

    return Date.parse(dateString);
}

$(document).off('click', '.switchButton').on('click', '.switchButton', function()
{
    var taskViewType = $(this).attr('data-type');
    $.cookie.set('taskViewType', taskViewType, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});
