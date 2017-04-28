function syncModule(rootID, type)
{
    moduleID = type == 'task' ? $('#projectModule').val() : $('#productModule').val();
    type     = type == 'task' ? 'task' : 'story';

    link = createLink('tree', 'ajaxGetSonModules', 'moduleID=' + moduleID + '&rootID=' + rootID + '&type=' + type);
    $.getJSON(link, function(modules)
    {
        if(modules.length == 0) return false;
        $('.helplink').addClass('hidden');
        var $inputgroup = $('<div></div>').append($('.input-group .icon-remove:first').closest('.row-table').clone()).html();
        $.each(modules, function(key, module)
        {
            $('.row-table').each(function()
            {
               moduleName = $(this).find('input[id^=modules]').val();
                if(moduleName == module.name) modules[key] = null;
                if(!moduleName) $(this).closest('.row-table').remove();
            })
        });  

        $.each(modules, function(key, module)
        {
            if(module)
            {
                $('#sonModule').append($inputgroup);
                $('#sonModule .row-table:last input[id^=modules]').val(module.name);
                $('#sonModule .row-table:last input[id^=shorts]').val(module.short);
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
   var $copy = $('table.copy');
   if($copy.size() == 0) return false;
   $copy.toggle();
}

$(document).ready(function()
{
    toggleCopy();
    $('[data-id="create"] a').modalTrigger({type: 'iframe', width: 500});
    $('[data-id="edit"] a').modalTrigger({type: 'iframe', width: 500});
});
