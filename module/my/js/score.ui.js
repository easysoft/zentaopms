window.renderCell = function(result, {col, row})
{
    if(col.name == 'method')
    {
        result[0] = methods[row.data.module][row.data.method]
    }

    return result;
}
