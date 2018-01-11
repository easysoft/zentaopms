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
        $this->locate($this->createLink('mail', 'index'));
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
            die(js::reload('parent'));
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
}
