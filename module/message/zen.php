<?php
declare(strict_types=1);
/**
 * The zen file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easysoft.ltd>
 * @package     message
 * @link        https://www.zentao.net
 */
class messageZen extends message
{
    public function assignDropmenuVars(string $active = 'unread')
    {
        $messages = $this->message->getMessages('all', 'createdDate_desc');

        $unreadCount    = 0;
        $unreadMessages = $allMessages = array();
        array_map(function($message) use (&$unreadCount, &$unreadMessages, &$allMessages)
        {
            $date = substr($message->createdDate, 0, 10);

            $secondDiff = time() - strtotime($message->createdDate);
            if($secondDiff < 60)    $time = sprintf($this->lang->message->timeLabel['minute'], 1);
            if($secondDiff >= 60)   $time = sprintf($this->lang->message->timeLabel['minute'], ceil($secondDiff / 60));
            if($secondDiff >= 3600) $time = $this->lang->message->timeLabel['hour'];
            if($secondDiff >= 5400) $time = substr($message->createdDate, 11, 5);
            if($secondDiff > 86400) $time = substr($message->createdDate, 5, 11);
            $message->showTime = $time;

            preg_match_all("/<a href='([^\']+)'/", $message->data, $out);
            $link    = count($out[1]) ? $out[1][0] : '';
            $content = str_replace("<a href='$link'", "<a data-url='{$link}' href='###' onclick='clickMessage(this)'", $message->data);
            $content = preg_replace("/data-app='([^\']+)'/", '', $content);
            $content = preg_replace("/(\?|\&)onlybody=yes/", '', $content);
            $message->data = $content;

            $allMessages[$date][] = $message;
            if($message->status == 'read') return;

            $unreadCount++;
            $unreadMessages[$date][] = $message;
        }, $messages);

        $this->view->allMessages    = $allMessages;
        $this->view->unreadCount    = $unreadCount;
        $this->view->unreadMessages = $unreadMessages;
        $this->view->active         = $active;
    }
}
