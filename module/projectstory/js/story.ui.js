window.renderCell = function(result, info)
{
    if(info.col.name == 'status' && result)
    {
        result[0] = {html: `<span class='status-${info.row.data.rawStatus}'>` + info.row.data.status + "</span>"};
    }
    if(info.col.name == 'assignedTo' && info.row.data.status == 'closed')
    {
        delete result[0]['props']['data-toggle'];
        delete result[0]['props']['href'];
        result[0]['props']['className'] += ' disabled';
    }
    return result;
};

window.insertListToDoc = function()
{
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    let {cols, data} = dtable.options;
    data = data.filter((item) => checkedList.includes(item.id + ''));
    const docID = getDocApp()?.docID;

    const blockType = 'projectStory';
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
    if(!idList?.length) return;
    const dtable = zui.DTable.query($('#stories'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
