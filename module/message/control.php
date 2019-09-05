<?php
/**
 * The control file of message of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class message extends control
{
    /**
     * Index
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
     * Browser Setting
     * 
     * @access public
     * @return void
     */
    public function browser()
    {
        $browserConfig = $this->config->message->browser;

        if($_POST)
        {
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            $data = fixer::input('post')->get();

            $browserConfig = new stdclass();
            $browserConfig->turnon   = $data->turnon;
            $browserConfig->pollTime = $data->pollTime;

            $this->loadModel('setting')->setItems('system.message.browser', $browserConfig);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $response['locate'] = $this->createLink('message', 'browser');
            $this->send($response);
        }

        $this->view->title      = $this->lang->message->browser;
        $this->view->position[] = $this->lang->message->common;
        $this->view->position[] = $this->lang->message->browser;

        $this->view->browserConfig = $browserConfig;
        $this->display();
    }

    /**
     * Setting
     *
     * @access public
     * @return void
     */
    public function setting()
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $data->messageSetting = json_encode($data->messageSetting);
            $this->loadModel('setting')->setItem('system.message.setting', $data->messageSetting);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->loadModel('webhook');
        $this->loadModel('action');

        $this->view->title      = $this->lang->message->setting;
        $this->view->position[] = $this->lang->message->common;
        $this->view->position[] = $this->lang->message->setting;

        $users = $this->loadModel('user')->getPairs('noletter');
        unset($users['']);

        $this->view->users         = $users;
        $this->view->objectTypes   = $this->message->getObjectTypes();
        $this->view->objectActions = $this->message->getObjectActions();
        $this->display();
    }

    /**
     * Ajax get message.
     *
     * @access public
     * @return void
     */
    public function ajaxGetMessage($windowBlur = false)
    {
        if($this->config->message->browser->turnon == 0) die();

        $waitMessages = $this->message->getMessages('wait');
        $todos = $this->message->getNoticeTodos();
        if(empty($waitMessages) and empty($todos)) die();

        $messages = '';
        $newline  = $windowBlur ? "\n" : '<br />';
        $idList   = array();
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
            echo <<<EOT
<div class='alert alert-info with-icon alert-dismissable' style='width:380px; position:fixed; bottom:25px; right:15px; z-index: 9999;'>
   <i class='icon icon-envelope-o'>  </i>
   <div class='content'>{$messages}</div>
   <button type="button" class="close" data-dismiss="alert">×</button>
 </div>
EOT;
        }

        $this->dao->delete()->from(TABLE_NOTIFY)->where('objectType')->eq('message')->andWhere('status')->ne('wait')->exec();
    }
}
