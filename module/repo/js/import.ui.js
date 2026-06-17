window.refreshProvider = function(providerType)
{
    let type;
    const $originPicker = $('[name=origin]').zui('picker');
    const $providerPicker = $('[name=providerID]').zui('picker');

    if (providerType === undefined || providerType === null)
    {
        type = $originPicker.$.getValue();
    }
    else
    {
        type = providerType;
        $originPicker.$.setValue(type);
    }

    $.getJSON($.createLink('provider', 'ajaxGetProviders', "type=" + type), function(data)
    {
        $providerPicker.render({items: data.items});
        if(data.value)
        {
            $providerPicker.$.setValue(data.value);
        }
    });
}
