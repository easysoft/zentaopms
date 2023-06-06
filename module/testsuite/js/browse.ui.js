window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        if(row.data.type == 'public') result[0] = {html: "<span class='label success-pale'>" + authorList['public'] + '</span>'};
        if(row.data.type == 'private') result[0] = {html: "<span class='label primary-pale'>" + authorList['private'] + '</span>'};
        result[1] = {html: '<a href=' + $.createLink('testsuite', 'view', 'testsuiteID=' + row.data.id) + '>' + row.data.name + '</a>'};
    }

    return result;
}
