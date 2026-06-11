window.refreshProvider = function()
{
    const type = $('[name=origin]').val();
    $.getJSON($.createLink('provider', 'ajaxGetProviders', "type=" + type), function(data)
    {
        $('[name=providerID]').zui('picker').render({items: data.items});
        if(data.value)
        {
            console.log(data.value);
            $('[name=providerID]').zui('picker').$.setValue(data.value);
        }
    });
}
