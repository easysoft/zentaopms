window.renderCell = function(result, {col, row})
{
    if(col.name === 'osVersion')
    {
        const{osName, osVersion} = row.data;
        if(osName) result[0] = {html: hostLang[osName + 'List'][osVersion]};

        return result;
    }

    return result;
};
