window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort);
}

window.renderCell = function(result, {col, row})
{
    if(col.name === 'status')
    {
        let className = '';
        if(row.data.status == 'failure' || row.data.status == 'create_fail') className = 'status-doing';
        if(row.data.status == 'success') className = 'status-done';
        result[0] = {html:'<span class="' + className + '">' + result[0] + '</span>'};

        return result;
    }

    if(col.name === 'name')
    {
        if(row.data.testtask) result[0].props['data-size'] = 'lg';

        return result;
    }

    return result;
};