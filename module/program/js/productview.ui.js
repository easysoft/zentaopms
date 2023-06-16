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
        /* Remove class of checkbox. */
        result.splice(result.length - 1);

        /* Remove checkbox. */
        result.forEach(function(ele, idx)
        {
            if(!ele.props.class) return;
            if(ele.props.class.includes('checkbox')) result.splice(idx, 1);
        });

        result[result.length - 1] = row.data.name;

        return result;
    }

    if(col.name == 'totalProjects' && row.data.type !== 'product')
    {
        return [row.data.totalProjects];
    }

    return result;
}

window.iconRenderProductView = function(value, row)
{
    if(row.data.type === 'program')     return {className: 'icon icon-cards-view text-gray'};
    if(row.data.type === 'productLine') return {className: 'icon icon-lane text-gray'};

    return '';
}
