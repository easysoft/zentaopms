function deleteBlock(dashboard, data, block)
{
    zui.Modal.confirm(data.confirm).then(result =>
    {
        if(!result) return;
        const url = zui.formatString(data.url, block);
        $.ajaxSubmit({url: url, method: 'GET', onSuccess: () => dashboard.delete(block.id)});
    });
}

function editBlock(dashboard, data, block)
{
    zui.Modal.open({url: zui.formatString(data.url, block), size: data.size});
}

function createBlock(dashboard, data, block)
{
    zui.Modal.open({url: zui.formatString(data.url, block), size: data.size});
}

function resetBlocks(dashboard, data, block)
{
    zui.Modal.confirm(data.confirm).then(result =>
    {
        if(!result) return;
        const url = zui.formatString(data.url, block);
        $.ajaxSubmit({url: url, method: 'GET', onSuccess: () => loadComponent('#dashboard')});
    });
}

window.handleClickBlockMenu = function(info, block)
{
    const data = info.item.data;
    const type = data ? data.type : '';
    if(!type) return;

    if(type === 'delete') return deleteBlock(this, data, block);
    if(type === 'edit')   return editBlock(this, data, block);
    if(type === 'create') return createBlock(this, data, block);
    if(type === 'reset')  return resetBlocks(this, data, block);
}
