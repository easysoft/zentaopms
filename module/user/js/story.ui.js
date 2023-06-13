window.renderCell = function(result, info)
{
    if(info.col.name == 'status' && result) return [info.row.data.statusLabel];
    return result;
};
