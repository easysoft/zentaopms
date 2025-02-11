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
        result = [];
        if(!row.data.build.name) return result;

        let branchLabel = showBranch ? "<span class='label label-outline label-badge mr-1' title='" + row.data.build.branchName + "'>" + row.data.build.branchName + '</span> ' : '';
        result.push({html: branchLabel + "<a href='" + row.data.build.link + "' title='" + row.data.build.name + "'>" + row.data.build.name + '</a>'});
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

window.insertListToDoc = function()
{
    const dtable      = zui.DTable.query($('#releases'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    let {cols, data} = dtable.options;
    data = data.filter((item) => checkedList.includes(item.id + ''));
    const docID = getDocApp()?.docID;

    const blockType = 'productRelease';
    const url = $.createLink('doc', 'buildZentaoList', `docID=${docID}&type=${blockType}&blockID=${blockID}`);
    const formData = new FormData();
    formData.append('cols', JSON.stringify(cols));
    formData.append('data', JSON.stringify(data));
    formData.append('idList', checkedList.join(','));
    formData.append('url', insertListLink);
    $.post(url, formData, function(resp)
    {
        resp = JSON.parse(resp);
        if(resp.result == 'success')
        {
            const oldBlockID = resp.oldBlockID;
            const newBlockID = resp.newBlockID;
            zui.Modal.hide();
            window.insertZentaoList && window.insertZentaoList(blockType, newBlockID, null, oldBlockID);
        }
    });
}

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#releases'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
