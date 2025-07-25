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

window.clickItems = function(event)
{
    $(event.target).closest('ul').find('a[data-toggle=dropdown]').toggleClass('active', $(event.target).closest('menu').length);
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
    if(info.col.name == 'deadline' && result[0])
    {
        const current   = zui.createDate();
        const year      = zui.formatDate(current, 'yyyy');
        const today     = zui.formatDate(current, 'yyyy-MM-dd');
        const yesterday = zui.formatDate(convertStringToDate(today) - 24 * 60 * 60 * 1000, 'yyyy-MM-dd');

        let deadline = result[0];
        if(deadline.split('-').length == 2) deadline = year + '-' + deadline;
        if(deadline == today)
        {
            result[0] = {html: '<span class="label warning-pale rounded-full size-sm">' + todayLabel + '</span>'};
        }
        else if(deadline == yesterday)
        {
            result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + yesterdayLabel + '</span>'};
        }
        else if(deadline < yesterday)
        {
            result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + result + '</span>'};
        }
    }

    if(info.col.name == 'confirmed' && info.row.data.confirmed == 0) result[0] = {html: '<span class="text-gray">' + result[0] + '</span>'};

    if(info.col.name == 'title' && info.row.data.isShadowProduct !== undefined)
    {
        const openTab = info.row.data.isShadowProduct == '1' ? 'project' : 'product';
        result[0] = {html: '<a href="' + $.createLink(info.row.data.storyType, 'view', `id=${info.row.data.id}`) + '" data-app="' + openTab + '">' + info.row.data.title + '</a>'};
    }

    if(info.col.name == 'title' && info.row.data.module == 'attend') result[0] = {html: '<span>' + result[0].props.children + '</span>'};

    if(info.col.name == 'name' && typeof info.row.data.delay != 'undefined' && info.row.data.delay > 0) result[result.length] = {html:'<span class="label danger-pale ml-1 flex-none nowrap">' + delayWarning.replace('%s', info.row.data.delay) + '</span>', className: 'flex items-end', style: {flexDirection:"column"}};

    return result;
}

function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    dateString = dateString[1] + '/' + dateString[2] + '/' + dateString[0];

    return Date.parse(dateString);
}
