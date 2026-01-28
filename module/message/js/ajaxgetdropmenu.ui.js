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
    fetchMessage(true, $.createLink('message', 'ajaxGetDropmenu', 'active=all'));
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

window.clickMessage = function(obj, event)
{
    // Prevent default link behavior to avoid double navigation
    if(event)
    {
        event.preventDefault();
        event.stopPropagation();
    }

    let $obj = $(obj);
    let url  = $obj.attr('data-url').replace(/\?onlybody=yes/g, '').replace(/\&onlybody=yes/g, '');
    markRead(obj);
    $('#header #messageBar').trigger('click');
    $.apps.openApp(url);
    renderMessage();
};

window.renderMessage = function()
{
    let $unreadTab  = $('#messageTabs #unread-messages.tab-pane');
    let unreadCount = $unreadTab.find('.message-item.unread').length;
    if(typeof(unreadLangTempate) != 'undefined') $('[href="#unread-messages"] span').html(unreadLangTempate.replace(/%s/, unreadCount));
    if(unreadCount == 0)
    {
        $unreadTab.find('ul').addClass('hidden');
        if($unreadTab.find('.nodata').length == 0) $unreadTab.append(noDataHtml);
    }

    let $allTab  = $('#messageTabs #all-messages.tab-pane');
    let allCount = $allTab.find('.message-item:not(.hidden)').length;
    if(allCount == 0)
    {
        $allTab.find('ul').addClass('hidden');
        if($allTab.find('.nodata').length == 0) $allTab.append(noDataHtml);
    }

    $('#messageTabs .message-date').each(function()
    {
        if($(this).find('.message-item:not(.hidden)').length == 0) $(this).addClass('hidden');
    });

    updateAllDot(showCount);
};

window.hideContextMenu = function()
{
    if(thisContextmenu != null) thisContextmenu.hide();
    thisContextmenu = null;
    contextmenuEle  = null;
};

window.clickContextMenu = function(item)
{
    let action = item.value;
    let $this  = contextmenuEle;
    if(action == 'delete')     deleteMessage($this);
    if(action == 'markunread') markUnread($this);
};

let thisContextmenu = null;
let contextmenuEle  = null;
$(function()
{
    updateAllDot(showCount);

    /* Bind contextmenu for message dropdown. */
    $(document).on('contextmenu', '*', function(event)
    {
        hideContextMenu();
        let $this = $(this);
        if($this.hasClass('message-item') || $this.closest('.message-item').length)
        {
            event.preventDefault();
            event.stopPropagation();

            if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');
            thisContextmenu = zui.ContextMenu.show(
            {
                triggerEvent: event,
                items: $this.hasClass('unread') ? unreadContextMenu : readContextMenu,
                menu: { onClickItem: (info) => { clickContextMenu(info.item); }}
            });
            contextmenuEle = $this;
            return;
        }
    });

    /* Hidden dropdown and contextmenu. */
    $('#dropdownMessageMenu').on('click', function(event)
    {
        hideContextMenu();
        if($(event.target).closest('.messageSettingBox').length == 0 && $('#messageSettingDropdown-toggle').hasClass('open')) closeSettingDropdown();
    });
    $('#dropdownMessageMenu').on('scroll', function(event){hideContextMenu();});

    /* Adjust dropdown height when resize. */
    $(window).on('resize', function(event)
    {
        let maxHeight = $(window).height() - $('#header').height() - 5;
        $("#dropdownMessageMenu").css('height', maxHeight).css('max-height', maxHeight)
    });
});
