window.onRenderExecutionCell = function(result, info)
{
    if(info.col.name === 'name' && systemMode == 'ALM')
    {
        result[0].props.className = 'flex-1 w-0';
        result.push({html: '<span class="label size-sm circle ' + (info.row.data.type == 'stage' ? 'warning' : 'secondary') + '-pale">' + typeList[info.row.data.type] + '</span>'}, {className:'row-reverse'});
    }

    return result;
}

