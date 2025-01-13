window.insertListToDoc = function()
{
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    let {cols, data} = dtable.options;
    data = data.filter((item) => checkedList.includes(item.id + ''));
    const docID = getDocApp()?.docID;
    let blockType = 'planStory';
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
            const blockID = resp.blockID;
            zui.Modal.hide();
            window.insertZentaoList && window.insertZentaoList(blockType, blockID, null, true);
        }
    });
}

window.toggleCheckRows = function(idList)
{
    if(!idList?.length || this._rendered) return;
    this._rendered = true;
    const dtable = zui.DTable.query($('#stories'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
