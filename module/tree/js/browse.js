function syncModule(rootID, type)
{
    moduleID = type == 'task' ? $('#projectModule').val() : $('#productModule').val();
    type     = type == 'task' ? 'task' : 'story';

    link = createLink('tree', 'ajaxGetSonModules', 'moduleID=' + moduleID + '&rootID=' + rootID + '&type=' + type);
    $.getJSON(link, function(modules)
    {
        if(modules.length == 0) return false;

        $('.helplink').addClass('hidden');
        var $inputgroup = $('<div></div>').append($('.col-actions .icon-close:first').closest('.table-row').clone()).html();

        $.each(modules, function(key, module)
        {
            $('#sonModule > .table-row').each(function()
            {
                moduleName = $(this).find('input[id^=modules]').val();
                if(moduleName == module.name) modules[key] = null;
                if(!moduleName) $(this).closest('#sonModule > .table-row').not('.copy').remove();
            })
        });

        $.each(modules, function(key, module)
        {
            if(module)
            {
                /* Duplicate removal for mdoule name. */
                var unique = true;
                $('#sonModule > .table-row').not('.copy').each(function()
                {
                    if($(this).find('.table-col:first').find(':input').val() == module.name) unique = false;
                })

                if(unique)
                {
                    $('#sonModule').append($inputgroup);
                    $('#sonModule .table-row:last input[id^=modules]').val(module.name);
                    $('#sonModule .table-row:last input[id^=shorts]').val(module.short);
                }
            }
        })
        $('#sonModule').append($inputgroup);
    })
}

function syncProductOrProject(obj, type)
{
    if(type == 'product') viewType = 'story';
    if(type == 'project') viewType = 'task';
    link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + obj.value + "&viewType=" + viewType + "&branch=0&rootModuleID=0&returnType=json");
    $.getJSON(link, function(modules)
    {
        $('.helplink').addClass('hidden');
        $('#' + type + 'Module').empty();
        $.each(modules, function(key, value)
        {
            $('#' + type + 'Module').append('<option value=' + key + '>' + value + '</option')
        });
        $('#' + type + 'Module').trigger("chosen:updated");
    })
    $('#copyModule').attr('onclick', null);
    $('#copyModule').bind('click', function(){syncModule(obj.value, viewType)});
}

function toggleCopy()
{
   var $copy = $('.table-row.copy');
   if($copy.size() == 0) return false;
   $copy.toggle();
}

$(document).ready(function()
{
    toggleCopy();
    $('[data-id="edit"] a').modalTrigger({type: 'iframe', width: 500});
});
