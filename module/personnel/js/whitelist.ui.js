window.renderCell = function(result, {col, row})
{
    if(col.name == 'actions')
    {
        result[0]['props']['items'][0]['link'] = $.createLink(module, 'unbindWhitelist', 'id=' + row.data.id);
    }

    return result;
}
