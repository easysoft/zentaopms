window.handleAssignToMeTabShow = function()
{
    let activeMore = false;
    $(this).find('.menu-item a[data-toggle=tab]').each(function()
    {
        if($(this).hasClass('active'))
        {
            $(this).closest('.nav-item.nav-switch').find('a[data-toggle=dropdown] span').html($(this).html());
            $(this).closest('.nav-item.nav-switch').find('a[data-toggle=dropdown]').addClass('active');
            activeMore = true;
        }
    });
    if(!activeMore)
    {
        $(this).find('.nav-item a[data-toggle=dropdown] span').html(moreLabel);
        $(this).find('.nav-item a[data-toggle=dropdown]').removeClass('active');
    }
};

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
    if(info.col.name == 'deadline' && result[0])
    {
        const today     = zui.formatDate(zui.createDate(), 'yyyy-MM-dd');
        const yesterday = zui.formatDate(convertStringToDate(today) - 24 * 60 * 60 * 1000, 'yyyy-MM-dd');
        if(result[0] == today)
        {
            result[0] = {html: '<span class="label warning-pale rounded-full size-sm">' + todayLabel + '</span>'};
        }
        else if(result == yesterday)
        {
            result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + yesterdayLabel + '</span>'};
        }
        else if(result < yesterday)
        {
            result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + result + '</span>'};
        }
    }

    if(info.col.name == 'confirmed' && info.row.data.confirmed == 0) result[0] = {html: '<span class="text-gray">' + result[0] + '</span>'};

    return result;
}

function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];

    return Date.parse(dateString);
}
