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

window.renderProductPlanList = function(result, {col, row, value})
{
    if(col.name === 'execution')
    {
        if(result[0])
        {
            result[0] = {html: '<a class="btn ghost toolbar-item text-primary square size-sm" title="' + result[0] + '"><i class="icon icon-run"></i></a>'};
        }
    }

    return result;
}
