$(function()
{
    window.customCount  = $('#paramDiv .input-group').length;
    window.triggerCount = $('#triggerForm .trigger-box').length;
});
window.addItem = function(event)
{
    const obj        = $(event.target);
    const inputGroup = obj.closest('.input-group').clone();
    const newName    = window.customCount + 'custom';
    window.customCount ++;

    $(inputGroup).find('input.custom').attr('id', newName);
    $(inputGroup).find('input.paramName').val('');
    $(inputGroup).find('input[id="' + newName + '"]').next().attr('for', newName);
    obj.closest('.form-group').append($(inputGroup));
}

window.deleteItem = function(event)
{
    const $obj = $(event.target);
    if($('.delete-param').length > 1) $obj.closest('.input-group').remove();
}

/**
 * Show input, hidden select.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
window.setValueInput = function(event)
{
    const obj = event.target;
    if($(obj).prop('checked'))
    {
        $(obj).closest('.input-group').find('select').attr('disabled', true);
        $(obj).closest('.input-group').find('select').addClass('hidden');
        $(obj).closest('.input-group').find("input[name^='paramValue']").removeClass('hidden');
        $(obj).closest('.input-group').find("input[name^='paramValue']").removeAttr('disabled');
    }
    else
    {
        $(obj).closest('.input-group').find("input[name^='paramValue']").attr('disabled', true);
        $(obj).closest('.input-group').find("input[name^='paramValue']").addClass('hidden');
        $(obj).closest('.input-group').find('select').removeClass('hidden');
        $(obj).closest('.input-group').find('select').removeAttr('disabled');
    }
}

window.changeCustomField = function(event)
{
    let paramValue = $(event.target).val();
    paramValue = paramValue.substr(1).toUpperCase();
    $(event.target).prevAll('input').val(paramValue);
}

window.changeTriggerType = function(event)
{
    const $parentDom = $(event.target).closest('.trigger-box');
    if($parentDom.find('.linkage-fields').length) $parentDom.find('.linkage-fields').remove();

    const type = $(event.target).val();
    if(type == 'tag' && repo.SCM == 'Subversion') $parentDom.append(svnField);
    if(type == 'commit')   $parentDom.append(commentField);
    if(type == 'schedule') $parentDom.append(scheduleField);
    $parentDom.find('.hidden').removeClass('hidden');
}
