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
            $browserConfig->pollTime = $data->pollTime;

            $this->loadModel('setting')->setItems('system.message.browser', $browserConfig);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
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
            $this->loadModel('setting')->setItem('system.message.setting', $data->messageSetting);
            $this->setting->setItem('system.message.blockUser', $data->blockUser);
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

        $todos        = $this->message->getNoticeTodos();
        $waitMessages = $this->message->getMessages('wait');
        if(empty($waitMessages) && empty($todos)) return;

        $windowBlur = !empty($windowBlur) && $windowBlur != 'false';

        $messages = '';
        $idList   = array();
        $newline  = $windowBlur ? "\n" : '<br />';
        foreach($waitMessages as $message)
        {
            $messages .= $message->data . $newline;
            $idList[]  = $message->id;
        }
        $this->dao->update(TABLE_NOTIFY)->set('status')->eq('sended')->set('sendTime')->eq(helper::now())->where('id')->in($idList)->exec();

        foreach($todos as $todo) $messages .= $todo->data . $newline;

        if($windowBlur)
        {
            preg_match_all("/<a href='([^\']+)'/", $messages, $out);
            $link = count($out[1]) ? $out[1][0] : '';
            $messages = strip_tags($messages);
            echo json_encode(array('message' => $messages, 'url' => $link));
        }
        else
        {
            echo html_entity_decode("<div class='browser-message-content'>{$messages}</div>");
        }

        $this->dao->delete()->from(TABLE_NOTIFY)->where('objectType')->eq('message')->andWhere('status')->ne('wait')->exec();
    }
}
