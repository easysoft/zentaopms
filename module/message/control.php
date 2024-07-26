<?php
declare(strict_types=1);
/**
 * The control file of message of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class message extends control
{
    /**
     * 主页。
     * Index.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        foreach($this->lang->message->typeList as $type => $typeName)
        {
            if(isset($this->config->message->typeLink[$type]))
            {
                list($moduleName, $methodName) = explode('|', $this->config->message->typeLink[$type]);
                if(common::hasPriv($moduleName, $methodName)) $this->locate($this->createLink($moduleName, $methodName));
            }
        }

        if(common::hasPriv('message', 'setting')) $this->locate($this->createLink('message', 'setting'));
    }

    /**
     * 浏览器设置。
     * Browser setting.
     *
     * @access public
     * @return void
     */
    public function browser()
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();

            $browserConfig = new stdclass();
            $browserConfig->turnon   = $data->turnon;
            $browserConfig->pollTime = (int)$data->pollTime;

            if($browserConfig->turnon)
            {
                if(empty($browserConfig->pollTime)) $this->send(array('result' => 'fail', 'message' => array('pollTime' => sprintf($this->lang->error->notempty, $this->lang->message->browserSetting->pollTime))));
                if($browserConfig->pollTime < $this->config->message->browser->minPollTime) $this->send(array('result' => 'fail', 'message' => array('pollTime' => $this->lang->message->browserSetting->pollTimeTip)));
            }

            $this->loadModel('setting')->setItems('system.message.browser', $browserConfig);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => array('url' => $this->createLink('message', 'browser'), 'selector' => array('#header', '#mainContainer'))));
        }

        $this->view->title         = $this->lang->message->browser;
        $this->view->browserConfig = $this->config->message->browser;
        $this->display();
    }

    /**
     * 消息设置。
     * Message setting.
     *
     * @access public
     * @return void
     */
    public function setting()
    {
        if(strtolower($this->server->request_method) == "post")
        {
            $data = fixer::input('post')->get();
            $data->messageSetting = !empty($data->messageSetting) ? json_encode($data->messageSetting) : '';
            $data->blockUser      = !empty($data->blockUser) && is_array($data->blockUser) ? implode(',', $data->blockUser) : zget($data, 'blockUser', '');
            $this->loadModel('setting')->setItem('system.message.setting@' . $this->config->vision, $data->messageSetting);
            $this->setting->setItem('system.message.blockUser@' . $this->config->vision, $data->blockUser);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $users = $this->loadModel('user')->getPairs('noletter,noclosed');
        unset($users['']);

        $this->view->title         = $this->lang->message->setting;
        $this->view->users         = $users;
        $this->view->objectTypes   = $this->message->getObjectTypes();
        $this->view->objectActions = $this->message->getObjectActions();
        $this->display();
    }

    /**
     * Ajax: 获取消息。
     * Ajax get message.
     *
     * @param  string $windowBlur
     * @access public
     * @return void
     */
    public function ajaxGetMessage(string $windowBlur = 'false')
    {
        if($this->config->message->browser->turnon == 0) return;

        $this->message->deleteExpired();

        $todos        = $this->message->batchSaveTodoNotice();
        $waitMessages = array_merge($todos, $this->message->getMessages('wait'));
        $unreadCount  = $this->message->getUnreadCount();
        if(!$this->config->message->browser->show) return print(json_encode(array('newCount' => $unreadCount, 'showCount' => $this->config->message->browser->count)));
        if(empty($waitMessages)) return;

        $windowBlur = !empty($windowBlur) && $windowBlur != 'false';

        $messages = array();
        $newline  = $windowBlur ? "\n" : '<br />';
        foreach($waitMessages as $message) $messages[$message->id] = $message->data;
        $this->dao->update(TABLE_NOTIFY)->set('status')->eq('sended')->set('sendTime')->eq(helper::now())->where('id')->in(array_keys($messages))->exec();

        foreach($messages as $id => $message)
        {
            preg_match_all("/<a href='([^\']+)'/", $message, $out);
            $link = count($out[1]) ? $out[1][0] : '';
            $message = $windowBlur ? strip_tags($message) : str_replace("<a href='$link'", "<a data-url='{$link}' href='###' onclick='clickMessage(this)'", $message);
            $message = preg_replace("/data-app='([^\']+)'/", '', $message);

            $browserMessages[] = $windowBlur ? array('text' => $message, 'url' => $link) : "<div class='browser-message-content'><span class='text-secondary-500' data-id={$id}>{$message}</span></div>";
        }
        echo json_encode(array('newCount' => $unreadCount, 'messages' => $browserMessages, 'showCount' => $this->config->message->browser->count));
    }

    /**
     * Ajax: 获取消息下拉菜单。
     * Ajax get dropmenu.
     *
     * @param  string $active  unread|all
     * @access public
     * @return void
     */
    public function ajaxGetDropmenu(string $active = 'unread')
    {
        $messages = $this->message->getMessages('all', 'createdDate_desc');

        $unreadCount    = 0;
        $unreadMessages = $allMessages = array();
        array_map(function($message) use (&$unreadCount, &$unreadMessages, &$allMessages)
        {
            $date = substr($message->createdDate, 0, 10);
            $allMessages[$date][] = $message;
            if($message->status == 'read') return;

            $unreadCount++;
            $unreadMessages[$date][] = $message;
        }, $messages);

        $this->view->allMessages    = $allMessages;
        $this->view->unreadCount    = $unreadCount;
        $this->view->unreadMessages = $unreadMessages;
        $this->view->active         = $active;
        $this->display();
    }

    /**
     * Ajax: 标记消息已读。
     * Ajax mark read.
     *
     * @param  string $messageID  all|int
     * @access public
     * @return void
     */
    public function ajaxMarkRead(string $messageID)
    {
        if($messageID != 'all') $messageID = (int)$messageID;
        $this->dao->update(TABLE_NOTIFY)->set('status')->eq('read')->where('objectType')->eq('message')
            ->andWhere('toList')->eq(",{$this->app->user->account},")
            ->beginIF(is_int($messageID))->andWhere('id')->eq($messageID)->fi()
            ->exec();
    }

    /**
     * Ajax: 标记消息未读。
     * Ajax: Mark message to unread
     *
     * @param  int    $messageID
     * @access public
     * @return void
     */
    public function ajaxMarkUnread(int $messageID)
    {
        $messageID = (int)$messageID;
        $this->dao->update(TABLE_NOTIFY)->set('status')->eq('sended')->where('objectType')->eq('message')
            ->andWhere('toList')->eq(",{$this->app->user->account},")
            ->beginIF(is_int($messageID))->andWhere('id')->eq($messageID)->fi()
            ->exec();
    }

    /**
     * Ajax: 删除消息。
     * Ajax delete message.
     *
     * @param  string $messageID  all|allread|int
     * @access public
     * @return void
     */
    public function ajaxDelete(string $messageID)
    {
        if($messageID != 'all' || $messageID != 'allread') $messageID = (int)$messageID;
        $this->dao->delete()->from(TABLE_NOTIFY)->where('objectType')->eq('message')
            ->andWhere('toList')->eq(",{$this->app->user->account},")
            ->beginIF($messageID == 'allread')->andWhere('status')->eq('read')->fi()
            ->beginIF(is_int($messageID))->andWhere('id')->eq($messageID)->fi()
            ->exec();
    }

    /**
     * Ajax: 设置自己的消息配置。
     * Ajax: Set for oneself
     *
     * @access public
     * @return void
     */
    public function ajaxSetOneself()
    {
        if(empty($_POST)) return;

        $data = fixer::input('post')->setDefault('show', 0)->setDefault('count', 0)->get();
        if(!is_numeric($data->maxDays)) dao::$errors['maxDays'] = $this->lang->message->error->maxDaysFormat;

        $data->maxDays = (int)$data->maxDays;
        if($data->maxDays < 0) dao::$errors['maxDays'] = $this->lang->message->error->maxDaysValue;
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $account = $this->app->user->account;
        $this->loadModel('setting')->setItems("{$account}.message.browser", $data);

        $this->config->message->browser->maxDays = $data->maxDays;
        $this->message->deleteExpired();

        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "updateAllDot({$data->count});"));
    }
}
