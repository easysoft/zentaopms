window.drillModalApi = function(pivotID, originField, conditions, filterValues, value)
{
    const drillModalLink = $.createLink('pivot', 'drillModal', `pivotID=${pivotID}&colName=${originField}&drillFields=${conditions}&filterValues=${filterValues}&value=${value}`);
    zui.Modal.open({url: drillModalLink, size: 'lg', className: 'dark'});
};
