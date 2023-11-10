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

    return result;
};
