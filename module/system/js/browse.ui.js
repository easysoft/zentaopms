window.renderCell = function(result, {col, row})
{
    if(col.name === 'name' && row.data.integrated == 1)
    {
        const name = row.data.name;
        result[0] = {html: '<div title = "' + name + '">' + name + '<span class="icon icon-code-fork text-gray" title="集成应用"></span></div>'};
    }

    return result;
};
