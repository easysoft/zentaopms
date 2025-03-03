$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();

    checkedList.forEach((id) => {form.append('caseIdList[]', id);});

    if($(this).hasClass('ajax-btn'))
    {
        if($(this).hasClass('batch-delete-btn'))
        {
            zui.Modal.confirm(confirmBatchDelete).then((res) => {if(res) $.ajaxSubmit({url, data:form});});
        }
        else
        {
            $.ajaxSubmit({url, data:form});
        }
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

/**
 * 标题列显示额外的内容。
 * Display extra content in the title column.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {row, col})
{
    if(result)
    {
        if(col.name == 'title')
        {
            const data   = row.data;
            const module = this.options.customData.modules[data.module];
            if(data.color) result[0].props.style = 'color: ' + data.color;
            if(module) result.unshift({html: '<span class="label gray-pale rounded-full">' + module + '</span>'}); // 添加模块标签
        }
    }

    return result;
}

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#caselib'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}

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
    const dtable      = zui.DTable.query($('#caselib'));
    const myTable     = dtable.$;
    const checkedList = Object.keys(myTable.state.checkedRows);
    if(!checkedList.length) return;

    let {cols} = dtable.options;
    const data = checkedList.map(rowID => myTable._checkedRows[rowID]);
    const docID = getDocApp()?.docID;

    const url = $.createLink('doc', 'buildZentaoList', `docID=${docID}&type=caselib&blockID=${blockID}`);
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
            window.insertZentaoList && window.insertZentaoList('caselib', newBlockID, null, oldBlockID);
        }
    });
}
