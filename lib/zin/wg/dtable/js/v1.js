window.setMultipleCell = function(value, info)
{
    if(!$.isArray(value)) value = value.toString().split(',');

    value = value.filter((data) => data);
    if(!value.length) return value;

    pairs = info.col.setting.dataPairs;
    const result = [];
    const data   = $.isArray(value) ? value : value.split(',');
    $.each(data, function(_, value)
    {
        if(value && pairs[value]) result.push(pairs[value]);
    });
    return result.join(info.col.setting.delimiter);
};

window.checkedChange = function(changes)
{
    if(!this._checkedRows) this._checkedRows = {};
    Object.keys(changes).forEach((rowID) =>
    {
        const row = this.getRowInfo(rowID);
        if(row !== undefined) this._checkedRows[rowID] = row.data;
    });
}

window.insertListToDoc = function(tableID, blockType, blockID, insertListLink)
{
    const dtable      = zui.DTable.query($(tableID));
    const myTable     = dtable.$;
    const checkedList = Object.keys(myTable.state.checkedRows);
    if(!checkedList.length) return;

    let {cols} = dtable.options;
    const data = checkedList.filter(rowID => myTable._checkedRows[rowID] !== undefined).map(rowID => myTable._checkedRows[rowID]);
    const docID = getDocApp()?.docID;

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

window.insertListToAI = function(tableID, blockType)
{
    const dtable      = zui.DTable.query($(tableID));
    const myTable     = dtable.$;
    const checkedList = Object.keys(myTable.state.checkedRows);
    if(!checkedList.length) return;

    const data = checkedList.filter(rowID => myTable._checkedRows[rowID] !== undefined).map(rowID => {
        const item = myTable._checkedRows[rowID];
        if(blockType === 'case' && typeof item.id === 'string' && item.id.startsWith('case_'))
        {
            item.id = item.caseID || parseInt(item.id.replace('case_', ''));
        }
        return item;
    });
    const url = $.createLink('ai', 'ajaxBatchCreateKnowledge', `knowledgeLibID=${knowledgeLibID}`);

    const formData = new FormData();
    formData.append('type', blockType);
    formData.append('data', JSON.stringify(data));
    formData.append('idList', checkedList.join(','));

    $.ajaxSubmit({
        url,
        data: formData,
        onSuccess: function() {zui.Modal.hide();}
    });
}
