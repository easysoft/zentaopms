window.markRead = function(e)
{
    let $this = $(e.target);
    if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');
    let isUnread = $this.hasClass('unread');
    if(!isUnread) return;

    let messageID    = $this.data("id");
    let $messageItem = $('#messageTabs .message-item.unread[data-id="' + messageID + '"]');
    $messageItem.find('.label-dot.danger').removeClass('danger').addClass('gray');
    $messageItem.removeClass('unread');
    $.get($.createLink('message', 'ajaxMarkRead', "id=" + messageID));

    /* Rerender unread count. */
    $('messageTabs #unread-messages.tab-pane').find('.message-item[data-id="' + messageID + '"]').addClass('hidden');
    renderUnreadCount();
}

window.markAllRead = function(e)
{
    let $messageItem = $('#messageTabs .message-item.unread');
    $messageItem.find('.label-dot.danger').removeClass('danger').addClass('gray');
    $messageItem.removeClass('unread');
    $('#messageTabs #unread-messages.tab-pane .message-item').addClass('hidden');
    $.get($.createLink('message', 'ajaxMarkRead', "id=all"));
    renderUnreadCount();
}

window.deleteAllRead = function(e)
{
    let result = confirm(confirmDeleteLang);
    if(!result) return;

    let $messageItem = $('#messageTabs .message-item:not(.unread)');
    $messageItem.addClass('hidden');
    $.get($.createLink('message', 'ajaxDelete', "id=allread"));
    renderUnreadCount();
}

window.deleteMessage = function(e)
{
    let result = confirm(confirmDeleteLang);
    if(!result) return;

    let $this = $(e.target);
    if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');

    let messageID = $this.data("id");
    let $messageItem = $('#messageTabs .message-item[data-id="' + messageID + '"]');
    $messageItem.addClass('hidden');
    $.get($.createLink('message', 'ajaxDelete', "id=" + messageID));

    /* Rerender unread count. */
    renderUnreadCount();
}

window.renderUnreadCount = function()
{
    let $unreadTab = $('#messageTabs #unread-messages.tab-pane');
    let unreadCount = $unreadTab.find('.message-item.unread').length;
    let $messageBarDot = $('#messageBar .label-dot.danger');
    $('[href="#unread-messages"] span').html(unreadLangTempate.replace(/%s/, unreadCount));
    if($messageBarDot.html()) $messageBarDot.html(unreadCount);
    if(unreadCount == 0)
    {
        $messageBarDot.remove();
        $unreadTab.find('ul').addClass('hidden');
        $unreadTab.append("<div class='text-center text-gray'>" + noDataLang + "</div>");
    }

    let $allTab  = $('#messageTabs #all-messages.tab-pane');
    let allCount = $allTab.find('.message-item:not(.hidden)').length;
    if(allCount == 0)
    {
        $allTab.find('ul').addClass('hidden');
        $allTab.append("<div class='text-center text-gray'>" + noDataLang + "</div>");
    }
}
