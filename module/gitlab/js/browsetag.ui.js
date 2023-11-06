window.renderCell = function(result, info)
{
    if(info.col.name == 'name' && result && info.row.data.protected)
    {
        result.push({html: `<span class="label secondary roundedsecondary-full">${protectedTag}</span>`});
    }

    return result;
}
