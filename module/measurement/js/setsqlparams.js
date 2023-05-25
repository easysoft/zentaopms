
function toggleSelectList(obj)
{
    isSelect = $(obj).prop('checked');
    if(isSelect)
    {   
        $(obj).parents('td').find('select').removeClass('hidden');
    }   
    else
    {
        $(obj).parents('td').find('select').addClass('hidden');
    }   

    var optionType = $(obj).parents('td').find('select').val();
    var $tr = $(obj).closest('tr');
    controlType = isSelect ? 'select' : 'input';
    updateParamControl($tr, controlType, optionType);
}

function updateParamControl($tr, controlType, optionType)
{
    var defaultValue = $tr.find('#defaultValue').val();
    defaultValue     = window.btoa(defaultValue);
    $.get(createLink('measurement', 'ajaxGetParamControl', "controlType=" + controlType + "&optionType=" + optionType + '&defaultValue=' + defaultValue), function(data)
    {
        $tr.find("td:last").remove();
        $tr.find("td:last").remove();
        $tr.append(data);
        $tr.find("td input").each(function()
        {
            if($(this).hasClass('form-date')) $(this).datepicker();
        });
        $tr.find("td select").each(function()
        {
            if($(this).hasClass('chosen')) $(this).chosen();
        });
    });
}
