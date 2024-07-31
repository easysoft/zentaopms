window.updateAllDot = function(showCount)
{
    showCount      = showCount != '0';
    let dotStyle   = 'padding: 2px;';
    let rightStyle = showCount ? 'right: -10px;' : 'right: -2px;';
    if(!showCount) dotStyle += 'width: 5px; height: 5px;';

    let $unreadTab  = $('#messageTabs #unread-messages.tab-pane');
    let unreadCount = $unreadTab.find('.message-item.unread').length;
    if(unreadCount > 99) unreadCount = '99+';
    if(unreadCount < 10 && showCount) rightStyle = 'right: -5px;';

    dotStyle += showCount ? 'top: -3px; aspect-ratio: 0;' : 'top: -2px; aspect-ratio: 1 / 1;';
    dotStyle += rightStyle;

    let dotHtml = '<span class="label danger label-dot absolute' + (showCount ? ' rounded-sm' : '') + '" style="' + dotStyle + '">' + (showCount ? unreadCount : '') + '</span>';
    parent.$('#apps .app-container').each(function()
    {
        let $iframeMessageBar = $(this).find('iframe').contents().find('#messageBar');
        $iframeMessageBar.find('.label-dot.danger').remove();
        if(unreadCount) $iframeMessageBar.append(dotHtml);
    });
};

window.closeSettingDropdown = function()
{
    $('#dropdownMessageMenu #messageSettingDropdown-toggle.with-popover-show').trigger('click');
}

window.reloadSettingModal = function(showCount)
{
    $('#dropdownMessageMenu #messageSettingDropdown').find('form').removeClass('loading');

    updateAllDot(showCount);
    closeSettingDropdown();
}
