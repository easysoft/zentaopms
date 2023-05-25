$(document).on('click', '#reDesignButton', function()
{
    location.href = createLink('measurement', 'design', "measurementID=" + measurementID + "&step=1&type=reDesign");
});

$(document).on('change', "select[name^='options']", function()
{
    var $tr = $(this).closest('tr');
    var optionType  = $(this).val();
    updateParamControl($tr, 'select', optionType);
})

function setParams(data)
{
    if(data)
    {
        var tbodyHtml = $('#setParams table tbody').html();
        $('#tmpDataBox').append(tbodyHtml);
        $('#setParams table tbody').empty();

        var html = $('#templateBox table tbody tr.template').html();
        for(i in data)
        {
            if(data[i].indexOf('|')) 
            {
                params = data[i].split('|');
                paramName    = params[0];
                defaultValue = params[1];
            }
            else
            {
                paramName    = data[i];
                defaultValue = '';
            }
            if($('#tmpDataBox').find('#' + paramName).length)
            {
                $('#setParams table tbody').append($('#tmpDataBox').find('#' + paramName).prop('outerHTML'));
            }
            else
            {
                $('#setParams table tbody').append('<tr id=>' + paramName + html + '</tr>');
                $('#setParams table tbody tr:last td:first').find('span:first').html(paramName);
                $('#setParams table tbody tr:last td:first').find('input:hidden').val(paramName);
            }

            $('#setParams table tbody tr:last input[name^="defaultValue"]').val(defaultValue);

            var index = $('#setParams table tbody tr:last').index();
            $('#setParams table tbody tr:last td').eq(2).find('input:radio').attr('name', 'varType[' + index + ']');
        }
        $('#submit').prop('disabled', false);
        $('#setParams').modal('show');
    }
}

function hideParamForm()
{
    $('#setParams').modal('hide');
    location.href = createLink('measurement', 'design', "measurementID=" + measurementID + "&step=3");
}

function toggleSelectList(obj)
{
    controlType = $(obj).val();
    if(controlType == 'select')
    {   
        $(obj).parents('td').find('select').removeClass('hidden');
    }   
    else
    {
        $(obj).parents('td').find('select').addClass('hidden');
    }   

    var optionType = $(obj).parents('td').find('select').val();
    var $tr = $(obj).closest('tr');
    updateParamControl($tr, controlType, optionType);
}

function updateParamControl($tr, controlType, optionType)
{
    var defaultValue = $tr.find('[name^=defaultValue]').val();
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
