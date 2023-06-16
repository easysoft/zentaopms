window.footerGenerator = function()
{
    return [{children: summary, className: "text-dark"}, "flex", "pager"];
}

window.renderReleaseCountCell = function(result, {col, row})
{
    if(col.name === 'createdDate')
    {
        if(row.data.createdDate === '') return [''];
    }

    if(col.name === 'latestReleaseDate')
    {
        if(row.data.latestReleaseDate === '') return [''];
    }

    if(col.name == 'name' && row.data.type !== 'product')
    {
        if(row.data.type == 'productLine') result[3] = row.data.name;
        if(row.data.type == 'program') result[2] = row.data.name;

        return result;
    }

    if(col.name == 'totalProjects' && row.data.type !== 'product')
    {
        return [row.data.totalProjects];
    }

    return result;
}
