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
    else
    {
        postAndLoadPage(url, form);
    }
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

            totalEstimate += Number(task.estimate);
            totalConsumed += Number(task.consumed);
            if(task.status != 'cancel' && task.status != 'closed') totalLeft += Number(task.left);

            if(task.status == 'wait')
            {
                waitCount ++;
            }
            else if(task.status == 'doing')
            {
                doingCount ++;
            }

            totalCount ++;
        }
    })

    const summary = checkedIDList.length > 0 ? checkedSummary : pageSummary;
    return {
        html: summary.replace('%total%', totalCount)
            .replace('%wait%', waitCount)
            .replace('%doing%', doingCount)
            .replace('%estimate%', totalEstimate.toFixed(1))
            .replace('%consumed%', totalConsumed.toFixed(1))
            .replace('%left%', totalLeft.toFixed(1))
    };
}

window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';
    return sortLink.replace('{orderBy}', sort);
}
