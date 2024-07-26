window.markRead = function(obj)
{
    let $this = $(obj);
    if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');
    let isUnread = $this.hasClass('unread');
    if(!isUnread) return;

    let messageID    = $this.data("msgid");
    let $messageItem = $('#messageTabs .message-item.unread[data-msgid="' + messageID + '"]');
    $messageItem.find('.label-dot.danger').removeClass('danger').addClass('gray');
    $messageItem.removeClass('unread');
    $messageItem.attr('data-target', '#readContextMenu');
    $.get($.createLink('message', 'ajaxMarkRead', "id=" + messageID));

    /* Rerender unread count. */
    $('#messageTabs #unread-messages.tab-pane').find('.message-item[data-msgid="' + messageID + '"]').addClass('hidden');
    renderMessage();
};

window.markUnread = function(obj)
{
    let $this = $(obj);
    if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');
    let isUnread = $this.hasClass('unread');
    if(isUnread) return;

    let messageID    = $this.data("msgid");
    let $messageItem = $('#messageTabs .message-item[data-msgid="' + messageID + '"]');
    $messageItem.find('.label-dot.gray').removeClass('gray').addClass('danger');
    $messageItem.addClass('unread');
    $.get($.createLink('message', 'ajaxMarkUnread', "id=" + messageID));

    /* Rerender unread count. */
    renderMessage();
};

window.markAllRead = function()
{
    let $messageItem = $('#messageTabs .message-item.unread');
    $messageItem.find('.label-dot.danger').removeClass('danger').addClass('gray');
    $messageItem.removeClass('unread');
    $('#messageTabs #unread-messages.tab-pane .message-item').addClass('hidden');
    $.get($.createLink('message', 'ajaxMarkRead', "id=all"));
    renderMessage();
};

window.clearRead = function()
{
    let $messageItem = $('#messageTabs .message-item:not(.unread)');
    $messageItem.addClass('hidden');
    $.get($.createLink('message', 'ajaxDelete', "id=allread"));
    renderMessage();
};

window.deleteMessage = function(obj)
{
    let $this = $(obj);
    if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');

    let messageID = $this.data("msgid");
    let $messageItem = $('#messageTabs .message-item[data-msgid="' + messageID + '"]');
    $messageItem.removeClass('unread');
    $messageItem.addClass('hidden');
    $.get($.createLink('message', 'ajaxDelete', "id=" + messageID));

    /* Rerender unread count. */
    renderMessage();
};

window.clickMessage = function(obj)
{
    let $obj = $(obj);
    let url  = $obj.attr('data-url').replace(/\?onlybody=yes/g, '').replace(/\&onlybody=yes/g, '');
    markRead(obj);
    $('#header #messageBar').trigger('click');
    $.apps.openApp(url);
    rederMessage();
};

window.renderMessage = function()
{
    let $unreadTab  = $('#messageTabs #unread-messages.tab-pane');
    let unreadCount = $unreadTab.find('.message-item.unread').length;
    if(typeof(unreadLangTempate) != 'undefined') $('[href="#unread-messages"] span').html(unreadLangTempate.replace(/%s/, unreadCount));
    if(unreadCount == 0)
    {
        $unreadTab.find('ul').addClass('hidden');
        if($unreadTab.find('.nodata').length == 0) $unreadTab.append("<div class='text-center text-gray nodata'>" + noDataLang + "</div>");
    }

    let $allTab  = $('#messageTabs #all-messages.tab-pane');
    let allCount = $allTab.find('.message-item:not(.hidden)').length;
    if(allCount == 0)
    {
        $allTab.find('ul').addClass('hidden');
        if($allTab.find('.nodata').length == 0) $allTab.append("<div class='text-center text-gray nodata'>" + noDataLang + "</div>");
    }

    $('#messageTabs .message-date').each(function()
    {
        if($(this).find('.message-item:not(.hidden)').length == 0) $(this).addClass('hidden');
    });

    updateAllDot(showCount);
};

window.clickContextMenu = function(obj)
{
    let action = $(obj).attr('value');
    let $this  = $(contextmenu._element);
    if(action == 'delete')     deleteMessage($this);
    if(action == 'markunread') markUnread($this);
};

let contextmenu = null;
$(document).on('contextmenu', '.message-item', function(event){ contextmenu = $(this).zui('ContextMenu'); });
$('#dropdownMessageMenu').on('click', function(event){hideContextMenu();});

$(function() { updateAllDot(showCount) });
