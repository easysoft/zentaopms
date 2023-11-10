window.renderCell = function(result, {col, row})
{
    if(col.name === 'url')
    {
        result[0] = {html:'<a href="' + row.data.url + '" target="_blank">' + row.data.url + '</a>', style:{flexDirection:"column"}};

        return result;
    }

    return result;
};
