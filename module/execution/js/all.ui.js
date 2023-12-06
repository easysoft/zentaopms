$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('executionIDList[]', id.replace("pid", '')));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
}).off('click', '#actionBar .export').on('click', '#actionBar .export', function()
{
    const dtable = zui.DTable.query($('#table-execution-all'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});
});

const today = zui.formatDate(new Date(), 'yyyy-MM-dd');
window.onRenderCell = function(result, {col, row})
{
    if(col.name == 'nameCol')
    {
        const executionLink = $.createLink('execution', 'task', `executionID=${row.data.rawID}`);
        const executionType = typeList[row.data.type];

        let executionName   = `<span class='label secondary-pale'>${executionType}</span> `;
        executionName      += (!row.data.isParent) ? `<a href="${executionLink}" class="text-primary">${row.data.name}</a>` : row.data.name;
        executionName      += (!['done', 'closed', 'suspended'].includes(row.data.status) && today > row.data.end) ? `<span class="label danger-pale ml-1">${delayed}</span>` : '';

        result[result.length] = {html: executionName};
        return result;
    }

    return result;
}
