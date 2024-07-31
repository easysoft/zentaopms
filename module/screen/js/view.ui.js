window.drillModalApi = function(pivotID, originField, conditions, filterValues, value)
{
    let drillModalLink = $.createLink('pivot', 'drillModal', `pivotID=${pivotID}&colName=${originField}&status=published&drillFields=${conditions}&filterValues=${filterValues}&value=${value}`);
    drillModalLink = drillModalLink.replace(/\+/g, '%2B');
    zui.Modal.open({url: drillModalLink, size: 'lg', className: 'dark'});
};
