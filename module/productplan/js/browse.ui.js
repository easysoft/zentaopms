window.footerInfo = function(checkedIdList)
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
