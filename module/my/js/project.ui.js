window.onRenderProjectNameCell = function(result, info)
{
    if(info.col.name === 'name' && info.row.data.delay > 0)
    {
        result[0].props.className = 'overflow-hidden';
        result[result.length] = {html:'<span class="label danger-pale ml-1 flex-none nowrap">' + delayInfo.replace('%s', info.row.data.delay) + '</span>', className:'flex items-end', style:{flexDirection:"column"}};
    }

    return result;
}
