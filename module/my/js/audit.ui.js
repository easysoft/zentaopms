window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'title')
    {
        result.pop();
        result.push({html: '<a href=\'' + viewLink.replace('{module}', row.data.module).replace('{id}', row.data.id)  + '\' data-toggle="modal" data-size="lg">'+ row.data.title + '</a>'});
    }
    return result;
}

