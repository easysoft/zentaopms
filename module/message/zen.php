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

            $allMessages[$date][] = $message;
        }, $messages);

        $this->view->allMessages    = $allMessages;
        $this->view->unreadCount    = $unreadCount;
        $this->view->unreadMessages = $unreadMessages;
        $this->view->active         = $active;
    }
}
