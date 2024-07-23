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
    let $unreadTab = $this.closest('#unread-messages.tab-pane');
    if($unreadTab.hasClass('active')) $this.hide();
    renderUnreadCount();
}

window.deleteMessage = function(e)
{
    let $this = $(e.target);
    if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');

    let messageID = $this.data("id");
    let $messageItem = $('#messageTabs .message-item[data-id="' + messageID + '"]');
    $messageItem.hide();
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
        $unreadTab.find('ul').hide();
        $unreadTab.append("<div class='text-center text-gray'>" + noDataLang + "</div>");
    }
}
