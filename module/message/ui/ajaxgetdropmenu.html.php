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

jsVar('unreadLangTempate', $lang->message->unread);
jsVar('noDataLang', $lang->noData);
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
            $isUnread   = $message->status != 'read';
            $dotColor   = $isUnread ? 'danger' : 'gray';
            $secondDiff = time() - strtotime($message->createdDate);
            if($secondDiff < 60)    $time = sprintf($lang->message->timeLabel['minute'], 1);
            if($secondDiff >= 60)   $time = sprintf($lang->message->timeLabel['minute'], ceil($secondDiff / 60));
            if($secondDiff >= 3600) $time = $lang->message->timeLabel['hour'];
            if($secondDiff > 5400)  $time = substr($message->createdDate, 11, 5);

            preg_match_all("/<a href='([^\']+)'/", $message->data, $out);
            $link    = count($out[1]) ? $out[1][0] : '';
            $content = str_replace("<a href='$link'", "<a data-url='{$link}' href='###' onclick='clickMessage(this)'", $message->data);
            $content = preg_replace("/data-app='([^\']+)'/", '', $content);
            $content = preg_replace("/(\?|\&)onlybody=yes/", '', $content);

            $itemList[] = h::li
            (
                setClass('message-item border rounded-lg p-2 mt-2' . ($isUnread ? ' unread' : '')),
                setData('msgid', $message->id),
                set(array('zui-create' => "contextMenu")),
                setData('target', $isUnread ? '#unreadContextMenu' : '#readContextMenu'),
                row
                (
                    setClass('text-gray justify-between'),
                    cell(label(setClass("label-dot {$dotColor} mr-2")), $lang->message->browser),
                    cell($time, icon(setClass('ml-2 cursor-pointer delete-message-btn'), 'close'))
                ),
                div(setClass('pt-1'), html($content))
            );
        }
        $dateList[] = h::li(setClass('message-date mt-2'), $date, h::ul(setClass('list-unstyled'), $itemList));
    }
    return h::ul(setClass('list-unstyled'), $dateList);
};

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
        btn(set::size('sm'), set::type('link'), setClass('allMarkRead'), set::hint($lang->message->notice->allMarkRead), icon('eye')),
        btn(set::size('sm'), set::type('link'), setClass('clearRead'),   set::hint($lang->message->notice->clearRead),   icon('trash')),
        btn(set::size('sm'), set::type('link'), set::hint($lang->message->browserSetting->more), setData('target', '#messageSettingModal'), setData('toggle', 'modal'), setData('size', 'sm'), icon('cog-outline'))
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

modal
(
    setID('messageSettingModal'),
    setClass('text-black'),
    set::title($lang->message->browserSetting->more),
    form
    (
        set::url(inlink('ajaxSetOneself')),
        set::actions(array('submit')),
        formGroup
        (
            set::width('2/3'),
            setClass('content-center'),
            set::label($lang->message->browserSetting->show),
            switcher(set::name('show'), set::value(1), set::checked($config->message->browser->show)),
        ),
        formGroup
        (
            set::width('2/3'),
            setClass('content-center'),
            set::label($lang->message->browserSetting->count),
            switcher(set::name('count'), set::value(1), set::checked($config->message->browser->count)),
        ),
        formGroup
        (
            set::width('2/3'),
            set::label($lang->message->browserSetting->maxDays),
            inputControl
            (
                input(set::name('maxDays'), set::value($config->message->browser->maxDays)),
                set::suffixWidth('30'),
                set::suffix($lang->day),
            )
        )
    )
);
