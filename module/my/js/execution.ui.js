window.onRenderExecutionCell = function(result, info)
{
    if(info.col.name === 'name' && systemMode == 'ALM')
    {
        result.push({html: '<span class="label size-sm circle secondary-pale">' + typeList[info.row.data.type] + '</span>'}, {className:'row-reverse'});
    }

    return result;
}

