function syncModule(rootID)
{
    link = createLink('tree', 'ajaxGetSonModules', 'moduleID=' + $('#productModule').val() + '&rootID=' + rootID);
    $.getJSON(link, function(modules)
    {
        $('.helplink').addClass('hidden');
        $.each(modules, function(key, value)
        {   
            moduleName = value;
            $('.text-3').each(function()
            {
                if(this.value == moduleName) modules[key] = null;
                if(!this.value) $(this).parent().addClass('hidden');
            })
        });  
        $.each(modules, function(key, value)
        {  
            if(value) $('#sonModule').append("<span><input name=modules[] value=" + value + " style=margin-bottom:5px class=text-3 /><br /><span>");
        })
    })
}

function syncProduct(obj)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + obj.value + "&viewType=story&rootModuleID=0&returnType=json");
    $.getJSON(link, function(modules)
    {
        $('.helplink').addClass('hidden');
        $('#productModule').empty();
        $.each(modules, function(key, value)
        {  
            $('#productModule').append('<option value=' + key + '>' + value + '</option')
        }); 
    })
    $('#copyModule').attr('onclick', null);
    $('#copyModule').bind('click', function(){syncModule(obj.value)});
}

function syncProject(obj)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + obj.value + "&viewType=task&rootModuleID=0&returnType=json");
    $.getJSON(link, function(modules)
    {
        $('.helplink').addClass('hidden');
        $('#projectModule').empty();
        $.each(modules, function(key, value)
        {  
            $('#projectModule').append('<option value=' + key + '>' + value + '</option')
        }); 
    })
    $('#copyModule').attr('onclick', null);
    $('#copyModule').bind('click', function(){syncModule(obj.value)});
}

$(document).ready(function()
{
    $("a.iframe").colorbox({width:480, height:240, iframe:true, transition:'none'});
});
