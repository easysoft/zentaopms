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
jsVar('confirmDeleteLang', $lang->message->notice->confirmDelete);

$buildMessageList = function($messageGroup) use ($lang)
{
    if(empty($messageGroup)) return div(setClass('text-gray text-center'), $lang->noData);

    $dateList = array();
    foreach($messageGroup as $date => $messages)
    {
        $dateList[] = h::li(setClass('message-date font-bold'), $date);
        $itemList[] = array();
        foreach($messages as $message)
        {
            $isUnread   = $message->status != 'read';
            $dotColor   = $isUnread ? 'danger' : 'gray';
            $secondDiff = time() - strtotime($message->createdDate);
            if($secondDiff < 60)    $time = sprintf($lang->message->timeLabel['minute'], 1);
            if($secondDiff >= 60)   $time = sprintf($lang->message->timeLabel['minute'], ceil($secondDiff / 60));
            if($secondDiff >= 3600) $time = $lang->message->timeLabel['hour'];
            if($secondDiff > 5400)  $time = substr($message->createdDate, 5, 11);

            $itemList[] = h::li
            (
                setClass('message-item border rounded-lg p-2 mt-2' . ($isUnread ? ' unread' : '')),
                setData('id', $message->id),
                row
                (
                    setClass('text-gray justify-between'),
                    cell(label(setClass("label-dot {$dotColor} mr-2")), $lang->message->browser),
                    cell($time, icon(setClass('ml-2 cursor-pointer delete-message-btn'), 'close'))
                ),
                div(setClass('pt-1'), html($message->data))
            );
        }
        $dateList[] = h::li(h::ul(setClass('list-unstyled'), $itemList));
    }
    return h::ul(setClass('list-unstyled'), $dateList);
};

tabs
(
    setID('messageTabs'),
    setClass('text-black pt-1 px-5 pb-5 relative'),
    set::style(array('width' => '400px', 'background-color' => '#fff')),
    on::click('.delete-message-btn', 'deleteMessage'),
    on::click('.message-item', 'markRead'),
    on::click('.deleteAllRead', 'deleteAllRead'),
    on::click('.allMarkRead', 'markAllRead'),
    div
    (
        setClass('absolute top-2 right-5'),
        set::style(array('z-index' => '100')),
        btn(set::size('sm'), set::type('link'), setClass('allMarkRead'),   set::hint($lang->message->notice->allMarkRead),   icon('eye')),
        btn(set::size('sm'), set::type('link'), setClass('deleteAllRead'), set::hint($lang->message->notice->deleteAllRead), icon('trash')),
        btn(set::size('sm'), set::type('link'), set::url(createLink('message', 'ajaxSetOneself')), setData('toggle', 'modal'), setData('size', 'sm'), icon('cog-outline'))
    ),
    tabPane
    (
        set::key('unread-messages'),
        set::title(sprintf($lang->message->unread, $unreadCount)),
        set::active(true),
        $buildMessageList($unreadMessages)
    ),
    tabPane
    (
        set::key('all-messages'),
        set::title($lang->message->all),
        $buildMessageList($allMessages)
    )
);
