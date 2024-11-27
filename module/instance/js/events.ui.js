window.initComponent = function (instanceID)
{
    toggleLoading('#events-panel', true);
    const $picker = $('[name=component]').zui('picker');
    $picker.$.clear();

    $.getJSON($.createLink('instance', 'ajaxGetComponents', 'id=' + instanceID), function (items)
    {
        toggleLoading('#events-panel', false);
        $picker.render({items: items});
        if (typeof (items) !== 'undefined')
        {
            $picker.$.setValue(items.component);
        }
        showEvents(instanceID);
    });
};

window.showEvents = function (instanceID)
{
    const instanceComponent = $('[name=component]').val();
    const target = $.createLink('instance', 'showEvents', 'id=' + instanceID + "&component=" + instanceComponent);
    toggleLoading('#events-panel', true);
    $.getJSON(target, function (data)
    {
        toggleLoading('#events-panel', false);
        $('#events-content').html(data.data.map(item => item.lastSeen + ' ' + item.type + ' ' + item.reason + ' ' + item.object + ' ' + item.message).join('\n'));
    });
};
