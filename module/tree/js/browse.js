function syncModule()
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
            if(value) $('#moduleBox').append("<span><input name=modules[] value=" + value + " style=margin-bottom:5px class=text-3 /><br /><span>");
        })
    })
}
$(document).ready(function()
{
    $("a.iframe").colorbox({width:480, height:240, iframe:true, transition:'none'});
});
