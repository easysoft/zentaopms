window.renderCell = function(result, {col, row})
{
    if(col.name == 'system' && row.data.releases)
    {
        result[0] = {className: 'overflow-hidden', html: result[0]};
        result[result.length] = {html:'<span class="label gray-pale rounded-xl clip">' + integratedLabel + '</span>', className:'flex items-end', style:{flexDirection:"column"}};
    }

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
        result = [];
        if(!row.data.build.name) return result;

        let branchLabel = showBranch && typeof row.data.build.branchName != 'undefined' ? "<span class='label label-outline label-badge mr-1' title='" + row.data.build.branchName + "'>" + row.data.build.branchName + '</span> ' : '';
        if(isFromDoc || isFromAI)
        {
            result.push({html: branchLabel + row.data.build.name});
        }
        else
        {
            result.push({html: branchLabel + "<a href='" + row.data.build.link + "' title='" + row.data.build.name + "'>" + row.data.build.name + '</a>'});
        }
        return result;
    }

    if(col.name == 'project')
    {
        result = [];
        if(!row.data.projectName) return result;

        result.push({html: `<span title='${row.data.projectName}'>${row.data.projectName}</span>`});
        return result;
    }

    return result;
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
    if(['id', 'branchName', 'name', 'branch', 'status', 'date', 'desc', 'releasedDate', 'actions', 'system'].includes(cell.col.name) && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
}

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#releases'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
