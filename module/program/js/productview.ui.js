window.footerGenerator = function()
{
    return [{children: summary, className: "text-dark"}, "flex", "pager"];
}

window.renderReleaseCountCell = function(result, {col, row})
{
    if(col.name === 'createdDate' || col.name === 'latestReleaseDate')
    {
        if(row.data.createdDate === '') return [''];
    }

    return result;
}
