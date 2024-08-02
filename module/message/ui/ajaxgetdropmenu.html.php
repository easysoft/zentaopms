<?php
declare(strict_types=1);
/**
 * The ajaxGetDropmenu view file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

$noDataHtml = "<div class='text-gray text-center nodata'>{$lang->noData}</div>";

jsVar('unreadLangTempate', $lang->message->unread);
jsVar('noDataHtml', $noDataHtml);
jsVar('showCount', $config->message->browser->count);

$buildMessageList = function($messageGroup) use ($lang)
{
    if(empty($messageGroup)) return div(setClass('text-gray text-center nodata'), $lang->noData);

    $dateList = array();
    foreach($messageGroup as $date => $messages)
    {
        $itemList = array();
        foreach($messages as $message)
        {
            $isUnread = $message->status != 'read';
            $dotColor = $isUnread ? 'danger' : 'gray';

            $itemList[] = h::li
            (
                setClass('message-item break-all border rounded-lg p-2 mt-2' . ($isUnread ? ' unread' : '')),
                setData('msgid', $message->id),
                set(array('zui-create' => "contextMenu")),
                setData('target', $isUnread ? '#unreadContextMenu' : '#readContextMenu'),
                row
                (
                    setClass('text-gray justify-between'),
                    cell(label(setClass("label-dot {$dotColor} mr-2")), $lang->message->browser),
                    cell($message->showTime, icon(setClass('ml-2 cursor-pointer delete-message-btn'), 'close'))
                ),
                div(setClass('pt-1'), html($message->data))
            );
        }
        $dateList[] = h::li(setClass('message-date mt-2'), $date, h::ul(setClass('list-unstyled'), $itemList));
    }
    return h::ul(setClass('list-unstyled'), $dateList);
};

$browserSetting = $config->message->browser;
tabs
(
    setID('messageTabs'),
    setClass('text-black pt-2.5 px-5 pb-5 relative'),
    set::style(array('width' => '400px', 'background-color' => '#fff')),
    on::click('.delete-message-btn', 'deleteMessage(e.target)'),
    on::click('.message-item', 'markRead(e.target)'),
    on::click('.clearRead', 'clearRead'),
    on::click('.allMarkRead', 'markAllRead'),
    div
    (
        setClass('absolute top-3 right-5'),
        set::style(array('z-index' => '100')),
        btn(set::size('sm'), setClass('ghost allMarkRead'), set::hint($lang->message->notice->allMarkRead), icon('clear')),
        btn(set::size('sm'), setClass('ghost clearRead'),   set::hint($lang->message->notice->clearRead),   icon('trash')),
        dropdown
        (
            setID('messageSettingDropdown'),
            to::trigger(btn(set::icon('cog-outline'), set::hint($lang->message->browserSetting->more), setClass('ghost'), set::caret(false))),
            to::menu(menu
            (
                setClass('dropdown-menu w-52'),
                on::click('e.stopPropagation();'),
                form
                (
                    setClass('gap-1'),
                    set::url(inlink('ajaxSetOneself')),
                    set::actions(false),
                    formRow(setClass('font-bold border-b pb-2 pl-2 pt-2'), $lang->message->browserSetting->more),
                    formGroup
                    (
                        setStyle(array('align-items' => 'center')),
                        set::label($lang->message->browserSetting->show),
                        switcher(set::name('show'), set::value(1), set::checked($browserSetting->show)),
                    ),
                    formGroup
                    (
                        setStyle(array('align-items' => 'center')),
                        set::label($lang->message->browserSetting->count),
                        switcher(set::name('count'), set::value(1), set::checked($browserSetting->count)),
                    ),
                    formGroup
                    (
                        set::width('5/6'),
                        set::label($lang->message->browserSetting->maxDays),
                        inputControl(input(set::name('maxDays'), set::value($browserSetting->maxDays)), set::suffixWidth('30'), set::suffix($lang->day))
                    ),
                    formGroup
                    (
                        setClass('justify-center form-actions mt-2'),
                        btn(set::text($lang->save),   setStyle(array('min-width' => '20px')), setClass('primary size-sm'), set::btnType('submit')),
                        btn(set::text($lang->cancel), setStyle(array('min-width' => '20px')), setClass('size-sm'), set::type('button'), on::click('closeSettingDropdown'))
                    )
                )
            ))
        )
    ),
    tabPane
    (
        set::key('unread-messages'),
        set::title(sprintf($lang->message->unread, $unreadCount)),
        set::active($active == 'unread'),
        $buildMessageList($unreadMessages)
    ),
    tabPane
    (
        set::key('all-messages'),
        set::title($lang->message->all),
        set::active($active == 'all'),
        $buildMessageList($allMessages)
    )
);

menu(setClass('contextmenu text-black'), setID('unreadContextMenu'), set::items(array(array('text' => $lang->delete, 'value' => 'delete', 'onclick' => 'clickContextMenu(this)'))));
menu(setClass('contextmenu text-black'), setID('readContextMenu'),   set::items(array(array('text' => $lang->delete, 'value' => 'delete', 'onclick' => 'clickContextMenu(this)'), array('text' => $lang->message->markUnread, 'value' => 'markunread', 'onclick' => 'clickContextMenu(this)'))));
