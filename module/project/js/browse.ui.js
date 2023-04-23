window.footerGenerator = function()
{
    const statistic = langSummary;
    return [{children: statistic, className: "text-dark"}, "flex", "pager"];
}

window.programMenuOnClick = function(data, url)
{
    location.href = url.replace('%d', data.item.key);
}

window.renderReleaseCountCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        if(row.data.delay > 0) result[result.length] = {html:'<span class="label size-sm circle danger-pale">' + langPostponed + '</span>', className:'flex items-end w-full', style:{flexDirection:"column"}};
        return result;
    }

    if(col.name === 'storyCount')
    {
        result[result.length] = {html:'<span class="text-gray text-xs">SP</span>'};
        return result;
    }

    if(col.name === 'invested')
    {
        result[result.length] = {html:'<span class="text-gray text-xs">' + langManDay + '</span>'};
        return result;
    }

    return result;
}
