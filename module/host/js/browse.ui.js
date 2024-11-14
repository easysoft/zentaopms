window.renderCell = function(result, {col, row})
{
    if(col.name === 'serverRoom')
    {
        if(row.data.serverRoom) result[1].attrs.title = rooms[row.data.serverRoom];
    }
    if(col.name === 'osVersion')
    {
        const{osName, osVersion} = row.data;
        if(osName) result[0] = {html: hostLang[osName + 'List'][osVersion]};
    }

    return result;
};
