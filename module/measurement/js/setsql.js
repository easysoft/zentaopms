$().ready(function()
{
    $.setAjaxForm('#sqlForm', function(response)
    {
        if(response.result == 'success')
        {
            $('#responseBox').removeClass('text-danger').html(response.queryResult);
            $('#submit').prop('disabled', false).addClass('btn-primary');
            if(typeof(response.locate) != 'undefined') location.href = response.locate;
        }
        else
        {
            $('#responseBox').addClass('text-danger').html(response.errors);
        }
        return false;
    });

    $(document).on('click', '#testBtn', function()
    {
        $('#action').val('test');
        $('#sqlForm').submit();
    });

    $(document).on('click', '#submit', function()
    {
        $('#action').val('save');
        $('#sqlForm').submit();
    });


    $('#sql').change(function()
    {
        $('#submit').prop('disabled', true).removeClass('btn-primary');
        $.post(createLink('measurement', 'getsqlparams'), 
        {
             sql: $('#sql').val(), measurementID:measurementID},
             function(response)
             {
                 $('#paramBox').html(response);
             }
        );
    });
});

$('[name^=varType]').change();

function togglParamList(obj)
{
   var varType = $(obj).parents('td').find('select[name*=varType]');
   toggleSelectList(varType);
}

function toggleSelectList(obj)
{
    isSelect = $(obj).val() == 'select';
    if(isSelect)
    {
        $(obj).parents('td').find('select[name*=options]').removeClass('hidden');
    }
    else
    {
        $(obj).parents('td').find('select[name*=options]').addClass('hidden');
    }

    var optionType = $(obj).parents('td').find('select[name*=options]').val();
    var tr = $(obj).closest('tr');
    controlType = $(obj).val();
    updateParamControl(tr, controlType, optionType);
}

function updateParamControl(tr, controlType, optionType)
{
    var defaultValue = tr.find('[name^=defaultValue]').val();
    defaultValue     = window.btoa(defaultValue);
    $.get(createLink('measurement', 'ajaxGetParamControl', "controlType=" + controlType + "&optionType=" + optionType + '&defaultValue=' + defaultValue), function(data)
    {
        tr.find("td:last").remove();
        tr.find("td:last").remove();
        tr.append(data);
        tr.find("td input").each(function()
        {
            if($(this).hasClass('form-date')) $(this).datepicker();
        });
        tr.find("td select").each(function()
        {
            if($(this).hasClass('chosen')) $(this).chosen();
        });
    });
}

function submitForm(value)
{
    $('#action').val(value);
    $('#sqlForm').submit();
}
