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

$buildMessageList = function($messageGroup) use ($lang)
{
    if(empty($messageGroup)) return div(setClass('text-gray text-center'), $lang->noData);

    $dateList = array();
    foreach($messageGroup as $date => $messages)
    {
        $dateList[] = h::li(setClass('message-date font-bold'), $date);
    }
    return h::ul(setClass('list-unstyled'), $dateList);
};

tabs
(
    setID('messageTabs'),
    setClass('text-black pt-1 px-5 pb-5'),
    set::style(array('width' => '400px', 'background-color' => '#fff')),
    on::click('.delete-message-btn', 'deleteMessage'),
    on::click('.message-item', 'markRead'),
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
