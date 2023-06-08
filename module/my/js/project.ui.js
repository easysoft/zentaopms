window.onRenderProjectNameCell = function(result, info)
{
    if(info.col.name === 'name' && info.row.data.delay > 0)
    {
        result[result.length] = {html:'<span class="label size-sm circle danger-pale">' + delayInfo.replace('%s', info.row.data.delay) + '</span>', className:'flex items-end w-full', style:{flexDirection:"column"}};
    }

    return result;
}
