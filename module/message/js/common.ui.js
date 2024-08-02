window.updateAllDot = function(showCount)
{
    showCount      = showCount != '0';
    let dotStyle   = 'padding: 2px;';
    let rightStyle = showCount ? 'right: -10px;' : 'right: -2px;';
    if(!showCount) dotStyle += 'width: 5px; height: 5px;';

    let $unreadTab  = $('#messageTabs #unread-messages.tab-pane');
    let unreadCount = $unreadTab.find('.message-item.unread').length;
    if(unreadCount < 10 && showCount) rightStyle = 'right: -5px;';
    if(unreadCount > 99) unreadCount = '99+';

    dotStyle += showCount ? 'top: -3px; aspect-ratio: 0;' : 'top: -2px; aspect-ratio: 1 / 1;';
    dotStyle += rightStyle;

    let dotHtml = '<span class="label danger label-dot absolute' + (showCount ? ' rounded-sm' : '') + '" style="' + dotStyle + '">' + (showCount ? unreadCount : '') + '</span>';
    parent.$('#apps .app-container').each(function()
    {
        let $iframeMessageBar = $(this).find('iframe').contents().find('#messageBar');
        if($iframeMessageBar.length > 0)
        {
            $iframeMessageBar.find('.label-dot.danger').remove();
            if(unreadCount) $iframeMessageBar.append(dotHtml);
        }

        let $oldPage = $(this).find('iframe').contents().find('#oldPage');
        if($oldPage.length > 0)
        {
            $iframeMessageBar = $oldPage.find('iframe').contents().find('#messageBar');
            if($iframeMessageBar.length  == 0) return;

            $iframeMessageBar.find('.label-dot.danger').remove();
            if(unreadCount) $iframeMessageBar.append(dotHtml);
        }
    });
};

window.toggleSettingDropdown = function(isOpen)
{
    if(typeof(isOpen) == 'undefined') isOpen = $('#messageSettingDropdown-toggle').hasClass('open');
    $('#messageSettingDropdown-toggle').toggleClass('open', !isOpen);
    $('#messageSettingDropdown').toggleClass('show', !isOpen);
};

window.closeSettingDropdown = function(){ toggleSettingDropdown(true); }
window.reloadSettingModal = function(showCount)
{
    $('#dropdownMessageMenu #messageSettingDropdown').find('form').removeClass('loading');

    updateAllDot(showCount);
    closeSettingDropdown();
}
