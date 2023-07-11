const executionDropdownMap = new Map();

window.footerSummary = function(checkedIdList)
{
    if(!checkedIdList.length)
    {
        return {html: pageSummary, className: 'text-dark'};
    }

    var summary = checkedSummary.replace('%total%', checkedIdList.length);
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
