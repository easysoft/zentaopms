window.renderCell = function(result, {col, row})
{
    if(col.name === 'status')
    {
        let className = '';
        if(row.data.status == 'failure' || row.data.status == 'create_fail') className = 'status-doing';
        if(row.data.status == 'success') className = 'status-done';
        result[0] = {html:'<span class="' + className + '">' + result[0] + '</span>'};
    }

    if(col.name == 'name' && row.data.engine != 'jenkins')
    {
        const pipeline = row.data.pipeline;
        if(!pipeline) return result;
        const branch = JSON.parse(pipeline).reference;

        if(branch) result[1] = {html:'<span class="label success-pale mr-1">' + branch + '</span>'};
    }

    return result;
};
