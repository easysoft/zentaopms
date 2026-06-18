window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'buildName' && (!row.data.execution || !row.data.executionMultiple))
    {
        if(result[0].props) result[0].props['data-app'] = 'project';
    }
    else if(col.name == 'status' && result)
    {
        result[0] = {html: `<span class='status-${row.data.rawStatus}'>` + row.data.status + "</span>"};
    }
    return result;
}
