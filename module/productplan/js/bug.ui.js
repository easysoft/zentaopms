window.insertListToDoc = function()
{
    const dtable      = zui.DTable.query($('#bugs'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    let {cols, data} = dtable.options;
    data = data.filter((item) => checkedList.includes(item.id + ''));
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
            window.replaceZentaoList && window.replaceZentaoList(oldBlockID, blockType, newBlockID, null);
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
