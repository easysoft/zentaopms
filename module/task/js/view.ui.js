window.waitDom('body.body-modal .toolbar', function()
{
    $('.body-modal .toolbar a[data-load="modal"]').attr('data-toggle', 'modal').removeAttr('data-load');
})

window.renderCell = function(result, info)
{
    const task = info.row.data;
    if(info.col.name == 'name' && result)
    {
        if(typeof result[0] == 'object') result[0].props.className = 'overflow-hidden';
        if(typeof task.delay != 'undefined' && task.delay && !['done', 'cancel', 'close'].includes(task.status))
        {
            result[result.length] = {html:'<span class="label danger-pale ml-1 flex-none nowrap">' + delayWarning.replace('%s', task.delay) + '</span>', className:'flex items-end', style:{flexDirection:"column"}};
        }
    }
    if(info.col.name == 'deadline' && result[0])
    {
        if(['done', 'cancel', 'close'].includes(task.status)) return result;

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
