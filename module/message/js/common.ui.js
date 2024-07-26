window.updateAllDot = function(showCount)
{
    let dotStyle = 'top: 5px; left: 18px; padding: 2px; aspect-ratio: ' + (showCount != '0' ? '0;' : '1 / 1;');
    if(showCount == 0) dotStyle += 'width: 5px; height: 5px;';

    let $unreadTab  = $('#messageTabs #unread-messages.tab-pane');
    let unreadCount = $unreadTab.find('.message-item.unread').length;
    if(unreadCount > 99) unreadCount = '99+';

    let dotHtml = '<span class="label danger label-dot absolute" style="' + dotStyle + '">' + (showCount != '0' ? unreadCount : '') + '</span>';
    parent.$('#apps .app-container').each(function()
    {
        let $iframeMessageBar = $(this).find('iframe').contents().find('#messageBar');
        $iframeMessageBar.find('.label-dot.danger').remove();
        if(unreadCount) $iframeMessageBar.append(dotHtml);
    });
};
