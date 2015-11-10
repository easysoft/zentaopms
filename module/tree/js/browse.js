function syncModule(rootID, type)
{
    moduleID = type == 'task' ? $('#projectModule').val() : $('#productModule').val();
    type     = type == 'task' ? 'task' : 'story';

    link = createLink('tree', 'ajaxGetSonModules', 'moduleID=' + moduleID + '&rootID=' + rootID + '&type=' + type);
    $.getJSON(link, function(modules)
    {
        $('.helplink').addClass('hidden');
        var $inputgroup = $('<div></div>').append($('.input-group .icon-remove:first').closest('.input-group').clone()).html();
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
            if(value)
            {
                $('#sonModule').append($inputgroup);
                $('#sonModule .input-group:last input').val(value);
            }
        })
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

function addItem(obj)
{
    var $inputgroup = $(obj).closest('.input-group');
    $inputgroup.after($inputgroup.clone()).next('.input-group').find('input').val('');
}

function deleteItem(obj)
{
    if($(obj).closest('.input-group').parent().find('i.icon-remove').size() <= 1) return;
    $(obj).closest('.input-group').remove();
}

$(document).ready(function()
{
    toggleCopy();
//    $("#submenucreate").modalTrigger({type: 'iframe', width: 500});
//    $("#submenuedit").modalTrigger({type: 'iframe', width: 500});
});
