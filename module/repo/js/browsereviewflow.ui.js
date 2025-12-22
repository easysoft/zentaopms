window.renderReviewFlowCell = function(result, {col, row})
{
    if(col.name == 'branchType')
    {
        const branchTypeList = row.data.branchType.split(',');
        let html = '';
        branchTypeList.forEach(type => {
            if(branchTypePairs[type]) html += '<span class="label secondary-pale lg mr-1" title="' + branchTypePairs[type] + '">' + branchTypePairs[type] + '</span>';
        })

        result[0] = {html, className: 'overflow-x-auto'};
        return result;
    }
    return result;
}
