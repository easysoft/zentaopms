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

/**
 * 计算表格发布信息的统计。
 * Set release summary for table footer.
 *
 * @access public
 * @return object
 */
window.setStatistics = function()
{
    const rows    = this.layout.allRows;
    let total     = 0;
    let normal    = 0;
    let terminate = 0;
    rows.forEach(function(row)
    {
        if(row.data.status === 'normal') normal ++;
        if(row.data.status === 'terminate') terminate ++;
        total ++;
    });

    let summary = '';
    if(type != 'all')
    {
        summary =  pageSummary.replace('%s', total);
    }
    else
    {
        summary = pageAllSummary.replace('%total%', total).replace('%normal%', normal).replace('%terminate%', terminate);
    }

    return {html: summary};
}
