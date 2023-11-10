const executionDropdownMap = new Map();

/**
 * 计算表格计划信息的统计。
 * Set plan summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIDList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checkedIDList, pageSummary)
{
    if(!checkedIDList.length) return {html: pageSummary, className: 'text-dark'};

    let total            = checkedIDList.length;
    let totalParent      = 0;
    let totalChild       = 0;
    let totalIndependent = 0;
    const rows  = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIDList.length == 0 || checkedIDList.includes(row.id))
        {
            const plan = row.data;

            if(plan.parent > 0) totalChild ++;
            if(plan.isParent)   totalParent ++;
        }
    });

    totalIndependent = total - totalParent - totalChild;

    let summary = checkedSummary.replace('%total%', total);
    summary     = summary.replace('%parent%', totalParent);
    summary     = summary.replace('%child%', totalChild);
    summary     = summary.replace('%independent%', totalIndependent);

    return {html: summary};
}

window.showExecution = function(target, executionList)
{
    if(executionDropdownMap.has(target)) return;

    let executionItems = new Array();
    executionList.forEach(function(execution, index)
    {
        const link = $.createLink('execution', 'task', `executionID=${execution.id}`);
        executionItems.push({text: execution.name, url: link});
    });

    const dropdown = new zui.Dropdown($(target), {
        arrow: true,
        placement: 'right',
        menu: {items: executionItems},
    });

    executionDropdownMap.set(target, dropdown);
}

window.renderProductPlanList = function(result, {col, row, value})
{
    if(col.name === 'execution')
    {
        if(result[0].length === 0) return [];

        if(result[0].length === 1)
        {
            const link = $.createLink('execution', 'task', `executionID=${result[0][0].id}`);
            result[0]  = {html: '<a class="btn ghost toolbar-item text-primary square size-sm" href="' + link + '" title="' + result[0][0].name + '"><i class="icon icon-run"></i></a>'};

            return result;
        }

        result[0] = {html: `<a class="btn ghost toolbar-item text-primary square size-sm" href="javascript:;" onclick='window.showExecution(this, ${JSON.stringify(result[0])})'><i class="icon icon-run"></i></a>`};
    }

    return result;
}

window.startProductPlan = function(planID)
{
    zui.Modal.confirm({message: confirmStart, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('productplan', 'start', 'planID=' + planID)});
    });
}

window.finishProductPlan = function(planID)
{
    zui.Modal.confirm({message: confirmFinish, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('productplan', 'finish', 'planID=' + planID)});
    });
}

window.activateProductPlan = function(planID)
{
    zui.Modal.confirm({message: confirmActivate, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('productplan', 'activate', 'planID=' + planID)});
    });
}

window.deleteProductPlan = function(planID)
{
    zui.Modal.confirm({message: confirmDelete, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('productplan', 'delete', 'planID=' + planID)});
    });
}

$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('planIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

$(document).on('click', 'button[data-target="#createExecutionModal"]', function()
{
    const planID = $(this).closest('.dtable-cell').data('row');
    $('#createExecutionModal [name=planID]').val(planID);
});

$(document).on('click', '#createExecutionButton', function()
{
    const projectID = $('input[name=project]').val();
    const planID    = $('input[name=planID]').val();

    openUrl($.createLink('execution', 'create', 'projectID=' + projectID + '&executionID=&copyExecutionID=&planID=' + planID + '&confirm=&productID=' + productID), {'app': 'execution'});
    zui.Modal.hide('#createExecutionModal');
});

/**
 * 对部分列进行重定义。
 * Redefine the partial column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, info)
{
    if(info.col.name == 'execution')
    {
        const projects     = info.row.data.projects;
        const projectCount = projects.length;
        if(projectCount == 0) return result;

        if(projectCount == 1)
        {
            result[0] = {html: '<a href=' + $.createLink('execution', 'task', 'executionID=' + projects[0].project) + ' title="' + projects[0].name + '"><i class="icon-run text-primary"></i></a>'};
        }
        else
        {
            let contentHtml = "<ul class='execution-tip'>";
            projects.forEach((project) => {
                contentHtml += `<li><a title='${project.name}' href='` + $.createLink('execution', 'task', 'executionID=' + project.project) + `'>${project.name}</a></li>`;
            });
            contentHtml += "</ul>";

            let content = {html: contentHtml};
            content = JSON.stringify(content);
            content = content.replace(/"/g, '&quot;');

            const buttonHtml = `<button type='button' data-toggle='popover' data-trigger='click' data-content="${content}" data-close-btn='false' data-placement='right'><i class='icon-run text-primary'></i></button>`;
            result[0] = {html: buttonHtml};
        }
    }

    return result;
}

$(document).on('click', '.switchButton', function()
{
    const type = $(this).data('type');
    $.cookie.set('viewType', type);
    loadCurrentPage();
})


window.getCol = function(col)
{
    col.subtitle = {html: "<span class='text-gray ml-1'>" + col.cards + "</span>"};
}

window.getItem = function(info)
{
    if(info.item.delay)
    {
        info.item.suffix      = expired;
        info.item.suffixClass = 'label danger rounded-xl' + (info.item.status == 'doing' ? ' mr-8' : '');
    }
    info.item.icon         = 'delay';
    info.item.titleAttrs   = {'class': 'text-black clip', 'title' : info.item.title};
    info.item.content      = {html: info.item.desc};
    info.item.contentClass = 'text-gray';
    info.item.footer       = {html: "<div class='flex'><span class='label label-" + info.item.status + "'>" + info.item.statusLabel + "</span><span class='label lighter ml-2'>" + info.item.dateLine + "</span></div>"};
    if(privs.canViewPlan) info.item.titleUrl = $.createLink('productplan', 'view', `id=${info.item.id}`);
}

window.canDrop = function(dragInfo, dropInfo)
{
    if(!dragInfo) return false;

    const column = this.getCol(dropInfo.col);
    const lane   = this.getLane(dropInfo.lane);
    if(!column || !lane) return false;

    console.log(dropInfo);
    if(dropInfo.type == 'item')             return false;
    if(dragInfo.item.lane != lane.name)     return false;
    if(dragInfo.item.status == 'wait'      && dropInfo.col == 'doing')  return privs.canStartPlan;
    if(dragInfo.item.status == 'wait'      && dropInfo.col == 'closed') return privs.canClosePlan;
    if(dragInfo.item.status == 'doing'     && dropInfo.col == 'done')   return privs.canFinishPlan;
    if(dragInfo.item.status == 'doing'     && dropInfo.col == 'closed') return privs.canClosePlan;
    if(dragInfo.item.status == 'done'      && dropInfo.col == 'doing')  return privs.canActivatePlan;
    if(dragInfo.item.status == 'done'      && dropInfo.col == 'closed') return privs.canClosePlan;
    if(dragInfo.item.status == 'closed'    && dropInfo.col == 'doing')  return privs.canActivatePlan;
    return false;
}

window.onDrop = function(changes, dropInfo)
{
    const item  = dropInfo['drag']['item'];
    const toCol = dropInfo['drop']['col'];

    if(item.status == 'wait' && toCol == 'doing')
    {
        zui.Modal.confirm(confirmStart).then(result =>
        {
            if(result)
            {
                const url = $.createLink('productplan', 'start', 'planID=' + item.id)
                $.ajaxSubmit({url});
                this.update(changes);
            }
        });
        return false;
    }
    else if(item.status == 'doing' && toCol == 'done')
    {
        zui.Modal.confirm(confirmFinish).then(result =>
        {
            if(result)
            {
                const url = $.createLink('productplan', 'finish', 'planID=' + item.id)
                $.ajaxSubmit({url});
                this.update(changes);
            }
        });
        return false;
    }
    else if((item.status == 'done' || item.status == 'closed') && toCol == 'doing')
    {
        zui.Modal.confirm(confirmActivate).then(result =>
        {
            if(result)
            {
                const url = $.createLink('productplan', 'activate', 'planID=' + item.id)
                $.ajaxSubmit({url});
                this.update(changes);
            }
        });
        return false;
    }

    zui.Modal.open({url: $.createLink('productplan', 'close', 'planID=' + item.id), size: 'lg'});
    return false;
}
