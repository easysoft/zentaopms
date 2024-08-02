<?php
/**
 * The ajaxGetDropmenuForOld view file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */

$noDataHtml       = "<div class='text-gray text-center nodata'>{$lang->noData}</div>";
$browserSetting   = $config->message->browser;
$buildMessageList = function($messageGroup) use ($lang, $noDataHtml)
{
    if(empty($messageGroup)) return $noDataHtml;

    $dateList = array();
    foreach($messageGroup as $date => $messages)
    {
        $itemList = array();
        foreach($messages as $message)
        {
            $isUnread = $message->status != 'read';
            $dotColor = $isUnread ? 'danger' : 'gray';

            $li  = "<li class='message-item border rounded-lg p-2 mt-2" . ($isUnread ? ' unread' : '') . "' data-msgid='{$message->id}' style='word-break: break-all;' onclick='markRead(this)'>\n";
            $li .= "<div class='text-gray relative'>";
            $li .= "<div><span class='label label-dot mr-2 {$dotColor}'></span><span>{$lang->message->browser}</span></div>\n";
            $li .= "<div class='absolute' style='top:0px; right:0px;'><span>{$message->showTime}</span><i class='icon icon-close ml-2 cursor-pointer delete-message-btn' onclick='deleteMessage(this)'></i></div>\n";
            $li .= "</div>\n";
            $li .= "<div class='pt-1'>{$message->data}</div>\n";
            $li .= "</li>\n";
            $itemList[] = $li;
        }
        $dateList[] = "<li class='message-date mt-2'>{$date}\n <ul class='list-unstyled'>" . implode("\n", $itemList) . "</ul>";
    }
    return "<ul class='list-unstyled'>" . implode("\n", $dateList) . "</ul>";
};
?>

<style>
#messageTabs.text-black {color: rgb(49, 60, 82);}
#messageTabs.pt-10px {padding-top:10px;}
#messageTabs.px-5 {padding-left: 20px; padding-right: 20px;}
#messageTabs.pb-5 {padding-bottom: 20px;}
#messageTabs .border {border-width: 1px; border-color: rgb(235, 237, 243); border-style: solid;}
#messageTabs .rounded-lg {border-radius: 6px;}
#messageTabs .p-2 {padding:8px;}
#messageTabs .pt-2 {padding-top:8px;}
#messageTabs .pl-2 { padding-left: 8px; }
#messageTabs .pb-2 { padding-bottom: 8px; }
#messageTabs .pt-2 { padding-top: 8px; }
#messageTabs .mt-2 {margin-top:8px;}
#messageTabs .mr-2 {margin-right:8px;}
#messageTabs .ml-2 {margin-left:8px;}
#messageTabs .cursor-pointer {cursor: pointer;}
#messageTabs .tabs-header{border-bottom-width: 1px;}
#messageTabs .label-dot{width:5px; height:5px; vertical-align: middle;}
#messageTabs .label-dot.gray { background-color: rgb(100, 117, 139); box-shadow: rgb(255, 255, 255) 0px 0px 0px 0px, rgb(100, 117, 139) 0px 0px 0px 1px, rgba(0, 0, 0, 0) 0px 0px 0px 0px;}
