window.customCount = 1;
function addItem(event)
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

function deleteItem(event)
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
function setValueInput(event)
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

function loadRepoList(engine = '')
{
    var link = $.createLink('job', 'ajaxGetRepoList', 'engine=' + engine);
    $.get(link, function(data)
    {
        if(data)
        {
            $('#repo').replaceWith(data)
            $('#repo_chosen').remove();
            $('#repo').chosen();
        }
    });
}
