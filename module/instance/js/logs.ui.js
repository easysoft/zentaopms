window.initComponent = function (instanceID) {
    toggleLoading('#logs-panel', true);
    const $picker = $('[name=component]').zui('picker');
    $picker.$.clear();

    $.getJSON($.createLink('instance', 'ajaxGetComponents', 'id=' + instanceID), function (items) {
        toggleLoading('#logs-panel', false);
        $picker.render({items: items});
        if (typeof (items) !== 'undefined') {
            $picker.$.setValue(items.component);
        }
    });
};

window.changeComponent = function (instanceID) {
    toggleLoading('#logs-panel', true);
    const $picker = $('[name=pod]').zui('picker');
    $picker.$.clear();
    const component = $('[name=component]').val();
    $.getJSON($.createLink('instance', 'ajaxGetPods', 'id=' + instanceID + '&component=' + component), function (items) {
        $picker.render({items: items});
        if (typeof (items) !== 'undefined') {
            $picker.$.setValue(items.pod);
        }
        toggleLoading('#logs-panel', false);
        showLogs(instanceID);
    });
};

window.showLogs = function (instanceID) {
    const instanceComponent = $('[name=component]').val();
    const instancePod = $('[name=pod]').val();
    const previous = $('[name=previous]').val();
    const target = $.createLink('instance', 'showlogs', 'id=' + instanceID + "&component=" + instanceComponent + "&pod=" + instancePod + "&previous=" + previous);
    toggleLoading('#logs-panel', true);
    $.getJSON(target, function (data) {
        toggleLoading('#logs-panel', false);
        $('#logs-content').html(data.data.map(item => item.timestamp + ' ' + item.content).join('\n'));
    });
};
