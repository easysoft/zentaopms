window.renderCell = function(result, info)
{
    if(info.col.name == 'title' && result)
    {
        const story = info.row.data;
        let html = '';

        let gradeLabel = '';
        const gradeMap = gradeGroup[story.type] || {};
        gradeLabel = gradeMap[story.grade];
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";

        if(story.color) result[0].props.style = 'color: ' + story.color;

        if(html) result.unshift({html});
    }

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
    const dtable = zui.DTable.query($('#stories'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
