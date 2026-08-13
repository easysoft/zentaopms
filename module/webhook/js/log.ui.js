window.renderCell = function(result, info)
{
    if(['url', 'result'].includes(info.col.name) && result)
    {
        const log = info.row.data;
        result[0]['attrs'] = {title: log[info.col.name]};
    }
    return result;
};
