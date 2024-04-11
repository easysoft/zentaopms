window.updateOrder = function(event, orders)
{
    let sortedIdList = {};
    for(let i in orders) sortedIdList['orders[' + orders[i] + ']'] = i;

    const moveModuleID = $(event.item).attr('z-key');
    $.post($.createLink('tree', 'updateOrder', 'rootID=' + rootID + '&viewType=' + viewType +'&moduleID=' + moveModuleID), sortedIdList);
}
