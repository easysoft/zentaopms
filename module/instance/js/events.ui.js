window.initComponent = function (instanceID)
{
    toggleLoading('#events-panel', true);
    const $picker = $('[name=component]').zui('picker');
    $picker.$.clear();

    $.getJSON($.createLink('instance', 'ajaxGetComponents', 'id=' + instanceID), function (items)
    {
        toggleLoading('#events-panel', false);
        $picker.render({items: items});
        if (typeof (items) !== 'undefined') $picker.$.setValue(items.component);
        if (items.length === 1) $('#component-events').addClass('hidden');
        showEvents(instanceID);
    });
};


window.showEvents = function (instanceID,noEventTip)
{
    const instanceComponent = $('[name=component]').val();
    const target = $.createLink('instance', 'showEvents', 'id=' + instanceID + "&component=" + instanceComponent);
    toggleLoading('#events-panel', true);
    $.getJSON(target, function (resp)
    {
        toggleLoading('#events-panel', false);
        $('#events-content').removeClass('dtable-empty-tip flex');
        if (resp.code !== 200 || resp.data.length === 0)
        {
            $('#events-content').html(noEventTip);
            $('#events-content').addClass('dtable-empty-tip flex');
            return;
        }
       $('#events-content').html(resp.data.map(item => item.lastSeen + ' ' + item.type + ' ' + item.reason + ' ' + item.object + ' ' + item.message).join('\n'));
       $('#events-content')[0].scrollTop = $('#events-content')[0].scrollHeight;
    });
};
