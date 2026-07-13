window.onRenderCell = function(result, {col, row})
{
    if(row.data.status == 'changed' && col.name == 'actions' && typeof result[0] != 'undefined')
    {
        if(result[0]['props']['items']) result[0]['props']['items'][0]['data-confirm'] = caseChangeTip.replace('%s', row.data.caseVersion);
    }

    return result;
}

$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('caseIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data:form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
})

$('.section-list').removeClass('pt-4 px-6');
