window.osChange = function()
{
    const osName = $('[name="osName"]').val();
    if(typeof osName === 'undefined') return;

    toggleLoading('#osVersion', true);
    const $picker = $('[name="osVersion"]').zui('picker');
    if(typeof $picker === 'undefined') return;

    $picker.$.clear();
    if(!osName)
    {
        $picker.render({items: []});
        toggleLoading('#osVersion', false);
        return;
    }

    $.getJSON($.createLink('host', 'ajaxGetOS', 'type=' + osName), function(items)
    {
        $picker.render({items: items});
        if(typeof(host) !== 'undefined') $picker.$.setValue(host.osVersion);
        toggleLoading('#osVersion', false);
    });
};
