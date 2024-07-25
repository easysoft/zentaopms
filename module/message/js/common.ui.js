window.updateAllDot = function(showCount)
{
    let $unreadTab  = $('#messageTabs #unread-messages.tab-pane');
    let unreadCount = $unreadTab.find('.message-item.unread').length;
    let dotHtml     = '<span class="label danger label-dot absolute" style="top: 5px; left: 18px; aspect-ratio: ' + (showCount != '0' ? '0' : '1 / 1') + '; padding: 2px;">' + (showCount != '0' ? unreadCount : '') + '</span>';
    parent.$('#apps .app-container').each(function()
    {
        let $iframeMessageBar = $(this).find('iframe').contents().find('#messageBar');
        $iframeMessageBar.find('.label-dot.danger').remove();
        if(unreadCount) $iframeMessageBar.append(dotHtml);
    });
};
