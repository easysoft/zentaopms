window.programMenuOnClick = function(data, url)
{
    location.href = url.replace('%d', data.item.key);
}

window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        if(row.data.delay > 0)
        {
            result[0].props.className = 'overflow-hidden';
            result[result.length] = {html:'<span class="label danger-pale ml-1 flex-none nowrap">' + delayWarning.replace('%s', row.data.delay) + '</span>', className:'flex items-end', style:{flexDirection:"column"}};
        }
        return result;
    }

    if(col.name === 'invested')
    {
        result[result.length] = {html:'<span class="text-gray text-xs">' + langManDay + '</span>'};
        return result;
    }

    return result;
}

window.footerSummary = function(element, checkedIDList)
{
    let totalCount = 0;
    const rows = element.layout.allRows;

    rows.forEach((row) =>
    {
        if(checkedIDList.length == 0 || checkedIDList.includes(row.id)) totalCount++;
    });

    const summary = checkedIDList.length > 0 ? checkedSummary.replace('{0}', totalCount) : totalCountTemplate.replace('{recTotal}', rows.length);
    return {html: summary};
}

window.handleBatchBtnClick = function(event)
{
    const $this = $(event.target).closest('a,.btn');
    const dtable = zui.DTable.query($this);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const form = new FormData();
    const url  = $this.data('url');
    checkedList.forEach((id) => form.append('projectIdList[]', id));
    postAndLoadPage(url, form);
};

window.handleClickExportBtn = function(event)
{
    const dtable = zui.DTable.query($('#table-project-browse'));
    const checkedList = dtable ? dtable.$.getChecks() : [];
    if(!checkedList.length) return;

    $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});
};

window.handleClickSwitchButton = function(event)
{
    const $this = $(event.target).closest('a,.btn');
    const projectType = $this.attr('data-type');
    $.cookie.set('projectType', projectType, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
};

window.handleChangeInvolved = function(event)
{
    const involved = $(event.target).is(':checked') ? 1 : 0;
    $.cookie.set('involved', involved, {expires:config.cookieLife, path:config.webRoot});
    loadTable();
};

/**
 * 提示并删除项目。
 * Delete project with tips.
 *
 * @param  int    projectID
 * @param  string projectName
 * @access public
 * @return void
 */
window.confirmDelete = function(projectID, projectName)
{
    zui.Modal.confirm({message: confirmDeleteTip.replace('%s', projectName), icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('project', 'delete', 'projectID=' + projectID)});
    });
};

window.changeProgram = function()
{
    const programID = $('input[name=programID]').val();
    const link      = $.createLink('project', 'browse', 'programID=' + programID + '&browseType=' + browseType + '&param=' + param + '&orderBy=order_asc&recTotal=' + recTotal + '&recPerPage=' + recPerPage + '&pageID=' + pageID);
    loadPage(link);
}
