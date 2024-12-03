window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        if(typeof result[0].props == 'object') result[0].props.className = 'clip w-max';
        if(row.data.postponed) result[result.length] = {html:'<span class="label size-sm circle danger-pale w-max">' + row.data.delayInfo + '</span>'};
        return result;
    }

    return result;
}

$(document).on('click', '#involved', function()
{
    var involved = $(this).prop('checked') ? 1 : 0;
    $.cookie.set('involved', involved, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});

window.confirmDelete = function(projectID, projectName)
{
    zui.Modal.confirm({message: confirmDeleteTip.replace('%s', projectName), icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('project', 'delete', 'projectID=' + projectID)});
    });
}

$(document).on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const form = new FormData();
    const url  = $(this).data('url');
    checkedList.forEach((id) => form.append('projectIdList[]', id));
    postAndLoadPage(url, form);
});

window.footerSummary = function(checkedIdList, pageSummary)
{
    return {html: pageSummary};
}
