window.initComponent = function (instanceID)
{
    toggleLoading('#logs-panel', true);
    const $picker = $('[name=component]').zui('picker');
    $picker.$.clear();

    $.getJSON($.createLink('instance', 'ajaxGetComponents', 'id=' + instanceID), function (items)
    {
        toggleLoading('#logs-panel', false);
        $picker.render({items: items});
        if (typeof (items) !== 'undefined')
        {
            $picker.$.setValue(items.component);
        }
    });
};

window.changeComponent = function (instanceID)
{
    toggleLoading('#logs-panel', true);
    const $picker = $('[name=pod]').zui('picker');
    $picker.$.clear();
    const component = $('[name=component]').val();
    $.getJSON($.createLink('instance', 'ajaxGetPods', 'id=' + instanceID + '&component=' + component), function (items)
    {
        $picker.render({items: items});
        if (typeof (items) !== 'undefined')
        {
            $picker.$.setValue(items.pod);
        }
        toggleLoading('#logs-panel', false);
        startAutoRefreshLogs(instanceID);
    });
};

window.showLogs = function (instanceID)
{
    const headerHeight = document.getElementById('logs-header').offsetHeight;
    document.getElementById('logs-content').style.height = `calc(100vh - ${headerHeight}px - 16px)`;
    const instanceComponent = $('[name=component]').val();
    const instancePod = $('[name=pod]').val();
    const previous = $('[name=previous]:checked').val();
    const target = $.createLink('instance', 'showlogs', 'id=' + instanceID + "&component=" + instanceComponent + "&pod=" + instancePod + "&previous=" + previous);
    $.getJSON(target, function (resp)
    {
        $('#logs-content').html(resp.data.map(item => item.timestamp + ' ' + item.content).join('\n'));
        $('#logs-content')[0].scrollTop = $('#logs-content')[0].scrollHeight;
    });
};

let logsTimer = null;

window.startAutoRefreshLogs = function(instanceID) {
    if(logsTimer) clearInterval(logsTimer);
    logsTimer = setInterval(function() {
        showLogs(instanceID);
    }, 5000);
}

$(window).on('unload', function() {
    if(logsTimer) clearInterval(logsTimer);
});

$(document).ready(function() {
    const instanceID = $('#logs-panel').data('id');
    if(instanceID) startAutoRefreshLogs(instanceID);
});

window.toggleAutoRefresh = function(instanceID) {
    const $toggleBtn = $('#autoRefreshBtn');
    if(logsTimer) {
        clearInterval(logsTimer);
        logsTimer = null;
        $toggleBtn.html('<i class="icon icon-play"></i>');
    } else {
        startAutoRefreshLogs(instanceID);
        $toggleBtn.html('<i class="icon icon-pause"></i>');
    }
}