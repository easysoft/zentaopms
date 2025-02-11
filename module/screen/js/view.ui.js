window.drillModalApi = function(pivotID, version, originField, conditions, filterValues, value)
{
    let drillModalLink = $.createLink('pivot', 'drillModal', `pivotID=${pivotID}&version=${version}&colName=${originField}&status=published&drillFields=${conditions}&filterValues=${filterValues}&value=${value}`);
    drillModalLink = drillModalLink.replace(/\+/g, '%2B');
    zui.Modal.open({url: drillModalLink, size: 'lg', className: 'dark bg-gray-200 bg-opacity-50'});
};
