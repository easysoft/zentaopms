window.renderCell = function(result, info)
{
    const task = info.row.data;
    if(info.col.name == 'status' && result) return [task.statusLabel];
    if(info.col.name == 'buildName' && result && task.build == 'trunk') return [trunkLang];
    return result;
};
