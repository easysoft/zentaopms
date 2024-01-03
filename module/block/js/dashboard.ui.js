$(function()
{
    if($('#expiredModal').length) zui.Modal.open({id: 'expiredModal'});
    if($('#annualModal').length)  zui.Modal.open({id: 'annualModal'});
});

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

/**
 * Load block from url.
 *
 * @param {string} id  Block ID.
 * @param {string} url Load url.
 */
window.loadBlock = function(id, url)
{
    $('#dashboard').dashboard('load', id, url);
};

/**
 * Handle layout change and save to server.
 *
 * @param {object} layout
 */
window.handleLayoutChange = function(layout)
{
    const form = new FormData();
    Object.keys(layout).forEach(key =>
    {
        const block = layout[key];
        form.append(`block[${key}][left]`, block.left);
        form.append(`block[${key}][top]`, block.top);
    });
    $.ajaxSubmit(
    {
        url:       $.createLink('block', 'layout'),
        data:      form
    });
};

/**
 * Handle block menu click.
 *
 * @param {object} info
 * @param {object} block
 */
window.handleClickBlockMenu = function(info, block)
{
    const data = info.item.data;
    const type = data ? data.type : '';
    if(!type) return;

    if(type === 'delete') return deleteBlock(this, data, block);
    if(type === 'edit')   return editBlock(this, data, block);
    if(type === 'create') return createBlock(this, data, block);
    if(type === 'reset')  return resetBlocks(this, data, block);
};
