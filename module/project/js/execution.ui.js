$(document).off('click', '#showTask').on('click', '#showTask', function()
{
    const show = $(this).is(':checked') ? 1 : 0;
    $.cookie.set('showTask', show, {expires:config.cookieLife, path:config.webRoot});

    if(show == 0 && status == 'bysearch')
    {
        loadPage($.createLink('project', 'execution', 'status=undone&projectID=' + projectID));
    }
    else
    {
        reloadPage();
    }
});

$(document).off('click', '#showStage').on('click', '#showStage', function()
{
    const show = $(this).is(':checked') ? 1 : 0;
    $.cookie.set('showStage', show, {expires:config.cookieLife, path:config.webRoot});

    reloadPage();
});

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
        $.ajaxSubmit({url, data:form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

const today = zui.formatDate(new Date(), 'yyyy-MM-dd');
window.onRenderCell = function(result, {col, row})
{
    if(col.name == 'nameCol')
    {
        const executionLink = $.createLink('execution', 'task', `executionID=${row.data.rawID}`);
        const executionType = typeList[row.data.type];
        let executionName   = '';
        if(typeof executionType != 'undefined') executionName += `<span class='label secondary-pale flex-none'>${executionType}</span> `;

        executionName += '<div class="ml-1 clip flex items-center" style="width: max-content;">';
        executionName += (executionType !== undefined && !row.data.isParent) ? `<a href="${executionLink}" class="text-primary">${row.data.name}</a>` : row.data.name;
        executionName += '</div>';
        executionName += (!['done', 'closed', 'suspended'].includes(row.data.status) && row.data.type != 'point' && row.data.end != '' && today > row.data.end) ? '<span class="label danger-pale ml-1 flex-none">' + (typeof row.data.delay != 'undefined' ? delayWarning.replace('%s', row.data.delay) : delayed) + '</span>' : '';

        result.push({html: executionName, className: 'w-full flex items-center'});
        return result;
    }
    if(col.name == 'rawID' && row.data.parent && !row.data.isExecution) result.push({className: 'ml-5'});
    if(['estimate', 'consumed', 'left'].includes(col.name) && result) result[0] = row.data.type == 'point' ? '' : {html: result[0] + ' h'};
    if(col.name == 'progress' && row.data.type == 'point') result[0] = '';

    return result;
}

window.footerSummary = function(element, checkedIdList)
{
    const rows = element.layout.rows;
    var totalCount = 0;
    var waitCount  = 0;
    var doingCount = 0;
    rows.forEach(function(data)
    {
        if(data.id.indexOf('tid') > -1) return;
        if(checkedIdList.length > 0 && checkedIdList.indexOf(data.id) > -1)
        {
            if(data.data.status == 'wait')  waitCount ++;
            if(data.data.status == 'doing') doingCount ++;
            totalCount ++;
        }
        else if(checkedIdList.length == 0)
        {
            if(data.data.status == 'wait')  waitCount ++;
            if(data.data.status == 'doing') doingCount ++;
            totalCount ++;
        }
    });

    return {html: (checkedIdList.length > 0 ? checkedExecSummary : pageExecSummary).replace('%total%', totalCount).replace('%wait%', waitCount).replace('%doing%', doingCount)};
};

window.confirmCreateStage = function(projectID, productID, executionID, hasChild)
{
    if(hasChild) loadPage($.createLink('programplan', 'create', `projectID=${projectID}&productID=${productID}&planID=${executionID}`));
    const link = $.createLink('project', 'ajaxCheckHasStageData', `executionID=${executionID}`);
    $.get(link, function(hasData)
    {
        if(hasData)
        {
            zui.Modal.confirm(confirmCreateStage).then((res) =>
            {
                if(res) loadPage($.createLink('programplan', 'create', `projectID=${projectID}&productID=${productID}&planID=${executionID}&executionType=stage&from=&syncData=1`));
            });
        }
        else
        {
            loadPage($.createLink('programplan', 'create', `projectID=${projectID}&productID=${productID}&planID=${executionID}`));
        }
    })
}
