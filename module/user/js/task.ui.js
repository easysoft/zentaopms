window.renderCell = function(result, info)
{
    const task = info.row.data;
    if(info.col.name == 'name' && result)
    {
        let html = '';
        if(task.team)   html += "<span class='label gray-pale rounded-xl'>" + multipleAB + "</span>";
        if(task.parent > 0) html += "<span class='label gray-pale rounded-xl'>" + childrenAB + "</span>";
        if(html) result.unshift({html});
    }
    if(info.col.name == 'deadline' && result[0])
    {
        if(result[0] == '0000-00-00') return [''];

        const delay     = typeof(task.delay) != 'undefined' ? 'delay' : '';
        const today     = zui.formatDate(zui.createDate(), 'yyyy-MM-dd');
        const yesterday = zui.formatDate(convertStringToDate(today) - 24 * 60 * 60 * 1000, 'yyyy-MM-dd');
        if(result[0] == today)     result[0] = {'html': '<span class="label warning-pale rounded-full ' + delay + ' size-sm">' + todayLabel + '</span>'};
        if(result[0] == yesterday) result[0] = {'html': '<span class="label danger-pale  rounded-full ' + delay + ' size-sm">' + yesterdayLabel + '</span>'};
        if(result[0] < yesterday)  result[0] = {'html': '<span class="label danger-pale  rounded-full ' + delay + ' size-sm">' + result + '</span>'};
    }
    return result;
};

function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];

    return Date.parse(dateString);
}
