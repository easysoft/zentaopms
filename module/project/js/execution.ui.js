$(document).off('click', '#showTask').on('click', '#showTask', function()
{
    const show = $(this).is(':checked') ? 1 : 0;
    $.cookie.set('showTask', show, {expires:config.cookieLife, path:config.webRoot});

    if(show == 0 && browseType == 'bysearch')
    {
        loadPage($.createLink('project', 'execution', 'browseType=undone&projectID=' + projectID));
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
    const data = row.data;
    if(col.name == 'nameCol')
    {
        const executionType = typeList[data.type];
        let html = '';
        if(typeof executionType != 'undefined') html += `<span class='label secondary-pale rounded-full mr-1 whitespace-nowrap'>${executionType}</span> `;
        if(typeof result[0] == 'object')
        {
            result[0].props.className = 'overflow-hidden';
            result[0].props.children  = data.type == 'point' ? '' : data.name;
            if(data.id.indexOf('tid') > -1 && data.type != 'point')
            {
                 result[0].props.children = data.rawName;
                 result[0].props.href     = $.createLink('task', 'view', 'taskID=' + data.rawID);
                 html += data.prefixLabel;
            }

            if(data.type == 'point' || (executionType !== undefined && data.isParent) || (data.type == 'kanban' && data.isTpl == '1'))
            {
                if(result[0].props.href !== undefined) delete result[0].props.href;
                result[0].type = 'span';

                if(data.type == 'point') html += data.name;
            }
        }
        if(typeof result[0] == 'string')
        {
            if(data.id.indexOf('tid') > -1 && data.type != 'point')
            {
                result[0] = data.rawName;
                html += data.prefixLabel;
            }
            if(data.type == 'point')
            {
                result[0] = '';
                html += data.name;
            }
        }

        result[1].attrs.title = typeof data.rawName != 'undefined' && data.rawName ? data.rawName : data.name;
        if(html) result.unshift({className: 'flex items-center', html: html});

        if(typeof data.delay != 'undefined' && data.delay > 0 && data.type != 'point' && data.end != '' && data.end != '0000-00-00' && today > data.end)
        {
            result.push({html: '<span class="label danger-pale ml-1 flex-none nowrap">' + delayWarning.replace('%s', data.delay) + '</span>', className: 'flex items-end', style: { flexDirection: "column" } });
        }
        return result;
    }
    if(col.name == 'rawID' && data.parent && !data.isExecution) result.push({className: 'ml-5'});
    if(['estimate', 'consumed', 'left', 'totalEstimate', 'totalConsumed', 'totalLeft'].includes(col.name) && result && typeof result[0] != 'undefined') result[0] = data.type == 'point' ? '' : {html: result[0] + ' h'};
    if(col.name == 'progress' && data.type == 'point') result[0] = '';
    if(col.name == 'taskSchedule' && row.data.taskSchedule == 'exception') result[0] = {html: '<span class="text-warning">' + result[0].props.children + '</span>'};

    return result;
}

window.formatExecHour = function(hours)
{
    const value = Math.round((Number(hours) || 0) * 100) / 100;
    return parseFloat(value.toFixed(2)).toString();
};

window.footerSummary = function(element, checkedIdList)
{
    const rows = element.layout.rows;
    if(checkedIdList.length > 0)
    {
        const checkedSet = {};
        checkedIdList.forEach(function(id){ checkedSet[id] = true; });

        const rowMap = {};
        rows.forEach(function(row){ rowMap[row.id] = row; });

        const hasCheckedAncestor = function(data)
        {
            let parentID = data.parent;
            const visited = {};
            while(parentID)
            {
                if(visited[parentID]) break;
                visited[parentID] = true;
                if(checkedSet[parentID]) return true;

                const parentRow = rowMap[parentID];
                parentID = parentRow && parentRow.data ? parentRow.data.parent : '';
            }
            return false;
        };

        var totalCount = 0;
        var estimate   = 0;
        var consumed   = 0;
        var left       = 0;
        rows.forEach(function(row)
        {
            if(String(row.id).indexOf('pid') !== 0) return;
            if(!checkedSet[row.id]) return;
            if(hasCheckedAncestor(row.data)) return;

            totalCount ++;
            estimate += Number(row.data.estimate) || 0;
            consumed += Number(row.data.consumed) || 0;
            left     += Number(row.data.left) || 0;
        });

        return {html: checkedExecSummary.replace('%total%', totalCount).replace('%estimate%', formatExecHour(estimate)).replace('%consumed%', formatExecHour(consumed)).replace('%left%', formatExecHour(left))};
    }

    var totalCount = 0;
    var waitCount  = 0;
    var doingCount = 0;
    rows.forEach(function(data)
    {
        if(data.id.indexOf('tid') > -1) return;
        if(data.data.status == 'wait')  waitCount ++;
        if(data.data.status == 'doing') doingCount ++;
        totalCount ++;
    });

    return {html: pageExecSummary.replace('%total%', totalCount).replace('%wait%', waitCount).replace('%doing%', doingCount)};
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
