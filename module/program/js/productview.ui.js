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

    return result;
}
