window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        if(typeof result[0].props == 'object') result[0].props.className = 'clip w-max';
        if(row.data.postponed) result[result.length] = {html:'<span class="label size-sm circle danger-pale w-max">' + row.data.delayInfo + '</span>'};
        return result;
    }

    if(col.name === 'budget')
    {
        let budgetHtml = `<div>${row.data.budget}</div>`;
        if(typeof(row.data.exceedBudget) != 'undefined')
        {
            let iconSign = ' <span class="icon icon-exclamation text-danger"></span>';
            let menu     = '<menu class="dropdown-menu custom">';
            let dropMenu = menu;
            dropMenu    += '<div class="mb-1"><span class="text-gray">' + projectBudgetLang + ': </span><span class="font-bold">' + row.data.rawBudget + '</span></div>';
            dropMenu    += '<div class="mb-1"><span class="text-gray">' + remainingBudgetLang + ': </span><span class="font-bold">' + row.data.remainingBudget + '</span></div>';
            dropMenu    += '<div class="text-danger">' + exceededBudgetLang + ': <span class="font-bold">' + row.data.exceedBudget + '</span></div>';

            if(row.data.type == 'program')
            {
                if(row.data.parent == 0) iconSign = ' <span class="icon icon-exclamation-sign text-danger"></span>';
                dropMenu  = menu;
                dropMenu += '<div class="mb-1"><span class="text-gray">' + programBudgetLang + ': </span><span class="font-bold">' + row.data.rawBudget + '</span></div>';
                dropMenu += '<div class="mb-1"><span class="text-gray">' + sumSubBudgetLang + ': </span><span class="font-bold">' + row.data.subBudget + '</span></div>';
                dropMenu += '<div class="text-danger">' + exceededBudgetLang + ': <span class="font-bold">' + row.data.exceedBudget + '</span></div>';
            }
            iconSign   = '<span data-toggle="dropdown" data-trigger="hover" data-placement="right-start">' + iconSign + '</span>';
            budgetHtml = `<div>${row.data.budget}${iconSign}${dropMenu}</div>`
        }
        result[0] = {html: budgetHtml, className:'flex w-full items-end mr-1', style:{flexDirection:"column"}};
        return result;
    }

    if(col.name === 'invested')
    {
        result[0] = {html: '<div>' + row.data.invested + ' <small class="text-gray">' + langManDay + '</small></div>', className:'flex w-full items-end', style:{flexDirection:"column"}};
        return result;
    }

    return result;
}

window.confirmDelete = function(projectID, module, projectName)
{
    let deleteURL = $.createLink(module, 'delete', "projectID=" + projectID);
    if(module == 'program')
    {
        $.ajaxSubmit(
            {
                url: deleteURL,
                onComplete: function(result)
                {
                    if(result.result == 'success') loadCurrentPage();
                }
            });
    }
    else
    {
        zui.Modal.confirm({message: confirmDeleteLang[module].replace('%s', projectName), icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
            {
                if(res) $.ajaxSubmit({url: deleteURL, load: true});
            });
    }
}

$(document).off('click', '[data-formaction]').on('click', '[data-formaction]', function()
{
    const $this       = $(this);
    const dtable      = zui.DTable.query($('#projectviews'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach(function(id)
    {
        let data = dtable.$.getRowInfo(id).data;
        if(data.type == 'program') return;
        postData.append('projectIdList[]', id);
    });

    if($this.data('page') == 'batch') postAndLoadPage($this.data('formaction'), postData);
});

window.footerSummary = function(element, checkedIdList)
{
    if(typeof(checkedIdList) == 'undefined') return {};
    if(typeof checkedIdList == 'string') return {html: checkedIdList, className: 'text-dark'};
    if(!checkedIdList || checkedIdList.length == 0) return {html: element.options.customData.pageSummary, className: 'text-dark'};

    const dtable      = zui.DTable.query($('#projectviews'));
    let totalProjects = 0;
    checkedIdList.forEach(function(id)
    {
        if(dtable)
        {
            let data = dtable.$.getRowInfo(id).data;
            if(data.type == 'program') return;
        }

        totalProjects++;
    });

    var summary = element.options.customData.checkedSummary.replace('%s', totalProjects);

    return {html: summary};
};

/**
 * 拖拽的项目集或者项目是否允许放下。
 * Is it allowed to drop the dragged program or project.
 *
 * @param  from   被拿起的元素
 * @param  to     放下时的目标元素
 * @access public
 * @return bool
 */
window.canSortTo = function(from, to)
{
    if(!from || !to) return false;
    if(from.data.parent != to.data.parent) return false;
    return true;
};

/**
 * 拖拽项目集或项目。
 * Drag program or project.
 *
 * @param  from   被拿起的元素                                                                                                                                                                                                                                                           * @param  to     放下时的目标元素
 * @param  type   放在目标元素的上方还是下方
 * @access public
 * @return bool
 */
window.onSortEnd = function(from, to, type)
{
    if(!from || !to) return false;
    if(!canSortTo(from, to)) return false;

    const url  = $.createLink('program', 'updateOrder');
    const form = new FormData();
    form.append('sourceID', from.data.id);
    form.append('targetID', to.data.id);
    form.append('type', type);
    $.ajaxSubmit({url, data:form});

    return true;
};
