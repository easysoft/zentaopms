$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
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
    else if($(this).hasClass('ajax-cancel-btn'))
    {
        $.ajaxSubmit({url, data: form}).then();
    }
    else
    {
        postAndLoadPage(url, form);
    }
}).off('click', '#actionBar .export').on('click', '#actionBar .export', function()
{
    const dtable = zui.DTable.query($('#table-execution-task'));
    if(!$('#table-execution-task').length) return;

    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});
});

/**
 * 计算表格任务信息的统计。
 * Set task summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIDList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checkedIDList)
{
    if(typeof element == 'undefined') return;

    let totalLeft     = 0;
    let totalEstimate = 0;
    let totalConsumed = 0;

    let waitCount  = 0;
    let doingCount = 0;
    let totalCount = 0;

    const rows  = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIDList.length == 0 || checkedIDList.includes(row.id))
        {
            const task = row.data;

            totalCount ++;
            if(task.rawStatus == 'wait')
            {
                waitCount ++;
            }
            else if(task.rawStatus == 'doing')
            {
                doingCount ++;
            }

            if(task.isParent > 0) return true;

            if(task.isParent == 0)
            {
                totalEstimate += Number(task.estimate);
                totalConsumed += Number(task.consumed);
            }

            if(task.rawStatus != 'cancel' && task.rawStatus != 'closed' && task.isParent == 0) totalLeft += Number(task.left);
        }
    })

    let summary = checkedIDList.length > 0 ? checkedSummary : pageSummary;
    summary =  summary.replace('%total%', totalCount)
        .replace('%wait%', waitCount)
        .replace('%doing%', doingCount)
        .replace('%estimate%', totalEstimate.toFixed(1))
        .replace('%consumed%', totalConsumed.toFixed(1))
        .replace('%left%', totalLeft.toFixed(1));

    $('.dtable-check-info').attr('title', summary.replace(/<[^>]+>/g,""));

    return {html: summary};
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
    const isFromDoc = this.props.isFromDoc;
    if(isFromDoc) return result;

    const task = info.row.data;
    if(info.col.name == 'name' && result)
    {
        let html = '';
        if(typeof result[0] == 'object') result[0].props.className = 'overflow-hidden';

        const module = this.options.modules[info.row.data.module];
        if(module) html += '<span class="label gray-pale rounded-full mr-1 whitespace-nowrap">' + module + '</span>'; // 添加模块标签

        if(task.mode)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + multipleAB + "</span>";
        }

        if(task.isParent > 0)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + parentAB + "</span>";
        }
        else if(task.parent > 0)
        {
            html += "<span class='label gray-pale rounded p-0 size-sm whitespace-nowrap'>" + childrenAB + "</span>";
        }

        if(task.color) result[0].props.style = 'color: ' + task.color;
        if(html) result.unshift({html});
        if(typeof task.delay != 'undefined' && task.delay && !['done', 'cancel', 'close'].includes(task.rawStatus))
        {
            result[result.length] = { html: '<span class="label danger-pale ml-1 flex-none nowrap">' + delayWarning.replace('%s', task.delay) + '</span>', className: 'flex items-end', style: { flexDirection: "column" } };
        }

        if(task.fromBug > 0 && !isFromDoc)
        {
            const bugLink  = $.createLink('bug', 'view', `id=${task.fromBug}`);
            const bugTitle = `<a class="bug" href='${bugLink}'>[BUG#${task.fromBug}]</a>`;
            result.push({html: bugTitle});
        }
    }
    if(info.col.name == 'status' && result)
    {
        result[0] = {html: `<span class='status-${info.row.data.rawStatus}'>` + info.row.data.status + "</span>"};
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
    if(info.col.name == 'assignedTo' && result)
    {
        if(task.mode == 'multi' && !task.assignedTo && !['done,closed'].includes(task.rawStatus))
        {
            if(canAssignTo) result[0]['props']['children'][1]['props']['children'] = teamLang;
            if(!canAssignTo) result[0] = teamLang;
        }
        if(typeof task.canAssignTo != 'undefined' && !task.canAssignTo && typeof result[0] == 'object')
        {
            let taskAssignTo = typeof this.props.userMap[task.assignedTo] != 'undefined' ? this.props.userMap[task.assignedTo] : task.assignedTo;
            result[0] = {html: `<span class='text-left ml-7'>` + taskAssignTo + "</span>", className: 'flex'};
        }
    }

    if(['estimate', 'consumed','left'].includes(info.col.name) && result) result[0] = {html: result[0] + ' h'};
    if(info.col.name == 'design')
    {
        result[0] = {html: task.designName};
        result[1].attrs['title'] = task.designName;
    }

    return result;
}

$(document).off('click', '.switchButton').on('click', '.switchButton', function()
{
    var taskViewType = $(this).attr('data-type');
    $.cookie.set('taskViewType', taskViewType, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#tasks'));

    dtable.$.toggleCheckRows(idList.split(','), true);
}
