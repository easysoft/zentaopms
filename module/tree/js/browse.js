function syncModule(rootID, type)
{
    moduleID = type == 'task' ? $('#projectModule').val() : $('#productModule').val();
    type     = type == 'task' ? 'task' : 'story';

    link = createLink('tree', 'ajaxGetSonModules', 'moduleID=' + moduleID + '&rootID=' + rootID + '&type=' + type);
    $.getJSON(link, function(modules)
    {
        $('.helplink').addClass('hidden');
        $.each(modules, function(key, value)
        {   
            moduleName = value;
            $('.form-control').each(function()
            {
                if(this.value == moduleName) modules[key] = null;
                if(!this.value) $(this).parent().remove();
            })
        });  

        $.each(modules, function(key, value)
        {  
            if(value) $('#sonModule').append("<span><input type='text' name='modules[]' value='" + value + "' style='margin-bottom:5px' class='form-control' /><span>");
        })
    })
}

function syncProductOrProject(obj, type)
{
    if(type == 'product') viewType = 'story';
    if(type == 'project') viewType = 'task';
    link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + obj.value + "&viewType=" + viewType + "&rootModuleID=0&returnType=json");
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

$(document).ready(function()
{
    $("#submenucreate").modalTrigger({type: 'iframe', width: 500});
    $("#submenuedit").modalTrigger({type: 'iframe', width: 500});
});
