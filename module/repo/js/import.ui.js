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

window.loadName = function()
{
    const $repoPicker = $('[name=repo]').zui('picker');
    const $nameInput  = $('[name=name]');
    if(typeof importName != 'undefined' && importName)
    {
        $nameInput.val(repoName);
        return;
    }

    const selections  = $repoPicker && $repoPicker.$ ? $repoPicker.$.state.selections : [];
    const repoName    = selections.length > 0 ? selections[0].text : '';

    $nameInput.val(repoName);
}

$(function()
{
    loadName();
});
