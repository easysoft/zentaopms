window.renderCell = function(result, info)
{
    if(info.col.name == 'buildName' && typeof result[0] == 'object' && (!info.row.data.execution || !info.row.data.executionMultiple))
    {
        result[0].props['data-app'] = 'project';
    }
    return result;
}
