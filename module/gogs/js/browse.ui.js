window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        var browseProjectLink = $.createLink('gogs', 'view', 'gogsID=' + row.data.id);
        if(canBrowseProject) result[0] = {html:'<a href="' + browseProjectLink + '" data-toggle="modal" data-width="40%">' + row.data.name + '</a>', style:{flexDirection:"column"}};

        return result;
    }

    if(col.name === 'url')
    {
        result[0] = {html:'<a href="' + row.data.url + '" target="_blank">' + row.data.url + '</a>', style:{flexDirection:"column"}};

        return result;
    }

    return result;
};
