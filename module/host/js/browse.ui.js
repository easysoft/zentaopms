window.renderCell = function(result, {col, row})
{
    if(col.name === 'osVersion')
    {
        const{osName, osVersion} = row.data;
        if(osName && osVersion) result[0] = {html: hostLang[osName + 'List'][osVersion]};
    }

    return result;
};
