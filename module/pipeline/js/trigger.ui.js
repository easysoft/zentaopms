$(function()
{
    $('.linkage-fields.hidden').remove();
    $('.custom-fields.hidden').parent().remove();

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

    const triggers = [];
    $('input[name^=triggerType]').each(function()
    {
        if($(this).attr('id') != $(event.target).attr('id')) triggers.push($(this).val());
    });

    const type = $(event.target).val();
    if(triggers.includes(type))
    {
        zui.Modal.alert(triggerRepeat).then(() =>
        {
            let triggerList = {};
            $.extend(true, triggerList, triggerTypeList);
            triggers.forEach((trigger) => {
                if(triggerList[trigger]) delete triggerList[trigger];
            });
            $(event.target).zui('picker').$.setValue(triggerList ? Object.keys(triggerList)[0] : '');
        });
        return;
    }

    if(type != 'tag' || repo.SCM == 'Subversion') $parentDom.append(eval(`${type}Field`).replace(/%s/g, window.triggerCount));
    if(window[`${type}BlockInit`]) eval(`window.${type}BlockInit()`);

    $parentDom.find('.hidden:not(.delete-trigger)').removeClass('hidden');
}

window.toggleAutoRun = function(event)
{
    if($(event.target).prop('checked'))
    {
        $('input[name=autoRun]').val('0');
    }
    else
    {
        $('input[name=autoRun]').val('1');
    }
}

window.addTrigger = function()
{
    if($('#triggerForm .trigger-box').length >= Object.keys(triggerTypeList).length) return;

    $('#triggerForm .trigger-box').last().after(triggerField.replace(/%s/g, window.triggerCount));
    $('#triggerForm .trigger-box').removeClass('hidden');

    $('#triggerPicker' + window.triggerCount).addClass('form-group-wrapper picker-box');

    const triggers = [];
    $('input[name^=triggerType]').each(function(){triggers.push($(this).val());});

    let trigger = '';
    const options = Object.keys(triggerTypeList).map((type) => {
        if(!trigger && !triggers.includes(type)) trigger = type;
        return {value: type, text: triggerTypeList[type]}
    });
    new zui.Picker('#triggerPicker' + window.triggerCount, {
        items: options,
        name:  `triggerType[${window.triggerCount}]`
    });

    setTimeout(() =>
    {
        $('#triggerPicker' + (window.triggerCount - 1)).zui('picker').$.setValue(trigger);
    }, 50)

    window.triggerCount ++;
    if($('#triggerForm .trigger-box').length > 1) $('.delete-trigger').removeClass('hidden');
    if($('#triggerForm .trigger-box').length >= Object.keys(triggerTypeList).length) $('.add-trigger-btn').addClass('hidden');
}

window.deleteTrigger = function(event)
{
    $(event.target).closest('.trigger-box').remove();
    if($('#triggerForm .trigger-box').length <= 1) $('.delete-trigger').addClass('hidden');
    if($('#triggerForm .trigger-box').length < Object.keys(triggerTypeList).length)$('.add-trigger-btn').removeClass('hidden');
}

window.tagBlockInit = function()
{
    if(repo.SCM != 'Subversion') return;

    $('#dirPicker' + window.triggerCount).addClass('form-group-wrapper picker-box');

    const options = Object.keys(dirs).map((dir) => {
        return {value: dir, text: dirs[dir]};
    });
    new zui.Picker('#dirPicker' + window.triggerCount, {
        items:         options,
        name:         `svnDir[]`,
        required:     true,
        defaultValue: job.svnDir
    });
}

window.scheduleBlockInit = function()
{
    $('#scheduleTime' + window.triggerCount).addClass('form-group-wrapper picker-box');
    new zui.TimePicker('#scheduleTime' + window.triggerCount, {
        name:         'atTime',
        defaultValue: job.atTime
    })
}
