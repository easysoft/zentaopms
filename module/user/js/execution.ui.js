window.renderCell = function(result, info)
{
    const execution = info.row.data;
    if(info.col.name == 'name' && result)
    {
        let html = '';
        if((edition == 'max' || edition == 'ipd') && typeof(executionTypeList[execution.type]) != 'undefined') html = "<span class='project-type-label label label-info label-outline'>" + executionTypeList[execution.type] + "</span>";
        if(html) result.unshift({html});
    }
    return result;
};
