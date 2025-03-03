window.checkedChange = function(changes)
{
    if(!this._checkedRows) this._checkedRows = {};
    Object.keys(changes).forEach((rowID) =>
    {
        const row = this.getRowInfo(rowID);
        if(row !== undefined) this._checkedRows[rowID] = row.data;
    });
}

window.insertListToDoc = function()
{
    const dtable      = zui.DTable.query($('#bugs'));
    const myTable     = dtable.$;
    const checkedList = Object.keys(myTable.state.checkedRows);
    if(!checkedList.length) return;

    let {cols} = dtable.options;
    const data = checkedList.map(rowID => myTable._checkedRows[rowID]);
    const docID = getDocApp()?.docID;

    let blockType = 'planBug';
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
    const dtable = zui.DTable.query($('#bugs'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
