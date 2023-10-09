window.renderCell = function(result, {col, row})
{
    if(col.name == 'name')
    {
        if(row.data.marker == 1)
        {
            result[result.length] = {html: "<icon class='icon icon-flag red' title='" + markerTitle + "'></icon>"};
            return result;
        }
    }

    if(col.name == 'build')
    {
        result[0] = '';
        if(!row.data.build.name) return result;

        let branchLabel = showBranch ? "<span class='label label-outline label-badge mr-1'>" + row.data.build.branchName + '</span> ' : '';
        result[result.length] = {html: branchLabel + "<a href='" + row.data.build.link + "' title='" + row.data.build.name + "'>" + row.data.build.name + '</a>'}
        return result;
    }

    if(col.name == 'project')
    {
        result[0] = '';
        if(row.data.builds.length == 0) return result;

        result[result.length] = {html: row.data.build.projectName};
        return result;
    }

    return result;
}

window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';
    return sortLink.replace('{orderBy}', sort);
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

/**
 * 合并单元格。
 * cell span in the column.
 *
 * @param  object cell
 * @access public
 * @return object
 */
window.getCellSpan = function(cell)
{
    if(['id', 'name', 'branch', 'status', 'date', 'actions'].includes(cell.col.name) && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
}
