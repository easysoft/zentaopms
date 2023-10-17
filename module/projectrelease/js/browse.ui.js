window.renderCell = function(result, {col, row})
{
    if(col.name == 'name')
    {
        if(row.data.marker == 1)
        {
            result[result.length] = {html: "<icon class='icon icon-flag text-danger' title='" + markerTitle + "'></icon>"};
            return result;
        }
    }

    if(col.name == 'build')
    {
        result[0] = '';
        for(key in row.data.buildInfos)
        {
            let buildName = canViewProjectbuild ?  "<a href='" + $.createLink('projectbuild', 'view', 'buildID=' + row.data.buildInfos[key].id) + "' title='" + row.data.buildInfos[key].name + "'>" + row.data.buildInfos[key].name + '</a>' : row.data.buildInfos[key].name;
            result[result.length] = {html: buildName}
        }
        return result;
    }

    if(col.name == 'branch')
    {
        result[0] = row.data.branchName
        return result;
    }

    return result;
}
