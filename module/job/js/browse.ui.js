window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort);
}

window.renderCell = function(result, {col, row})
{
    if(col.name === 'lastStatus')
    {
        let className = '';
        if(row.data.lastStatus == 'failure' || row.data.lastStatus == 'create_fail') className = 'status-doing';
        if(row.data.lastStatus == 'success') className = 'status-done';
        result[0] = {html:'<span class="' + className + '">' + result[0] + '</span>'};

        return result;
    }

    return result;
};