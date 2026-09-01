window.updateOrder = function(event, orders)
{
    let sortedIdList = {};
    for(let i in orders) sortedIdList['orders[' + orders[i] + ']'] = i;

    const moveModuleID = $(event.item).attr('z-key');
    $.post($.createLink('tree', 'updateOrder', 'rootID=' + rootID + '&viewType=' + viewType + '&moduleID=' + moveModuleID), sortedIdList);
}

window.addItem = function(e)
{
    const obj     = e.target
    const thisRow = $(obj).closest('.form-row');
    const newItem = thisRow.clone();

    $newBranch = newItem.find('.picker-box [name^=branch]');
    $pickerBox = null;
    if($newBranch.length > 0)
    {
        $pickerBox = $newBranch.closest('.picker-box');
        $pickerBox.removeAttr('id').removeAttr('data-zui-picker').empty();
    }

    $(obj).closest('.form-row').after(newItem);

    newItem.find('input[id^=modules]').attr('id', 'modules[]').attr('name', 'modules[]').val('');
    newItem.find('input[id^=shorts]').attr('id', 'shorts[]').attr('name', 'shorts[]').val('');
    newItem.find('input[id^=order]').attr('id', 'order[]').attr('name', 'order[]');
    newItem.find('.existing-actions.action-group').removeClass('existing-actions');

    if($pickerBox)
    {
        options = thisRow.find('.picker-box [name^=branch]').zui('picker').options;
        $pickerBox.picker($.extend({'name': 'branch[]'}, options));
    }
}
