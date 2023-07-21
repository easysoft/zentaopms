window.onRenderReleaseNameCell = function(result, info)
{
    if(info.col.name === 'name' && info.row.data.marker == '1')
    {
        result[result.length] = {html: "<icon class='icon icon-flag text-danger' title='" + markerTitle + "'></icon>"};
        return result;
    }
    return result;
}
