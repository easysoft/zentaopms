<?php
/**
 * The control file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     webhook 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class webhook extends control
{
    /**
     * Construct 
     * 
     * @param  string $moduleName 
     * @param  string $methodName 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('message');
    }

    /**
     * Browse webhooks. 
     * 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Unset selectedDepts cookie. */
        setcookie('selectedDepts', '', 0, $this->config->webRoot, '', false, true);

        $this->view->title      = $this->lang->webhook->api . $this->lang->colon . $this->lang->webhook->list;
        $this->view->webhooks   = $this->webhook->getList($orderBy, $pager);
        $this->view->position[] = html::a(inlink('browse'), $this->lang->webhook->api);
        $this->view->position[] = $this->lang->webhook->common;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Create a webhook. 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->webhook->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title      = $this->lang->webhook->api . $this->lang->colon . $this->lang->webhook->create;
        $this->view->products   = $this->loadModel('product')->getPairs();
        $this->view->projects   = $this->loadModel('project')->getPairs();
        $this->view->position[] = html::a(inlink('browse'), $this->lang->webhook->api);
        $this->view->position[] = html::a(inlink('browse'), $this->lang->webhook->common);
        $this->view->position[] = $this->lang->webhook->create;
        $this->display();
    }

    /**
     * Edit a webhook. 
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function edit($id)
    {
        if($_POST)
        {
            $this->webhook->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $webhook = $this->webhook->getByID($id);
        $this->app->loadLang('action');

        $this->view->title      = $this->lang->webhook->edit . $this->lang->colon . $webhook->name;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->webhook->api);
        $this->view->position[] = html::a(inlink('browse'), $this->lang->webhook->common);
        $this->view->position[] = $this->lang->webhook->edit;
        $this->view->products   = $this->loadModel('product')->getPairs();
        $this->view->projects   = $this->loadModel('project')->getPairs();
        $this->view->webhook    = $webhook;

        $this->display();
    }

    /**
     * Delete a webhook. 
     * 
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->webhook->delete(TABLE_WEBHOOK, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Browse logs of a webhook. 
     * 
     * @param  int    $id 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function log($id, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $uri   = $this->app->getURI(true);
        $this->session->set('productList',     $uri);
        $this->session->set('productPlanList', $uri);
        $this->session->set('releaseList',     $uri);
        $this->session->set('storyList',       $uri);
        $this->session->set('projectList',     $uri);
        $this->session->set('taskList',        $uri);
        $this->session->set('buildList',       $uri);
        $this->session->set('bugList',         $uri);
        $this->session->set('caseList',        $uri);
        $this->session->set('testtaskList',    $uri);
        $this->session->set('todoList',        $uri);

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $webhook = $this->webhook->getByID($id);
        $this->view->title      = $this->lang->webhook->log . $this->lang->colon . $webhook->name;
        $this->view->logs       = $this->webhook->getLogList($id, $orderBy, $pager);
        $this->view->position[] = html::a(inlink('browse'), $this->lang->webhook->api);
        $this->view->position[] = html::a(inlink('browse'), $this->lang->webhook->common);
        $this->view->position[] = $this->lang->webhook->log;
        $this->view->webhook    = $webhook;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Bind dingtalk userid.
     * 
     * @param  int    $id 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function bind($id, $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        if($_POST)
        {
            $this->webhook->bind($id);
            if(dao::isError()) die(js::error(dao::getError()));

            die(js::reload('parent'));
        }

        $webhook = $this->webhook->getById($id);
        if($webhook->type != 'dinguser' && $webhook->type != 'wechatuser')
        {
            echo js::alert($this->lang->webhook->note->bind);
            die(js::locate($this->createLink('webhook', 'browse')));
        }
        $webhook->secret = json_decode($webhook->secret);

        /* Get selected depts. */
        if($this->get->selectedDepts)
        {
            setcookie('selectedDepts', $this->get->selectedDepts, 0, $this->config->webRoot, '', false, true);
            $_COOKIE['selectedDepts'] = $this->get->selectedDepts;
        }
        $selectedDepts = $this->cookie->selectedDepts ? $this->cookie->selectedDepts : '';

        if($webhook->type == 'dinguser')
        {
            $this->app->loadClass('dingapi', true);
            $dingapi  = new dingapi($webhook->secret->appKey, $webhook->secret->appSecret, $webhook->secret->agentId);
            $response = $dingapi->getUsers($selectedDepts);
        }
        elseif($webhook->type == 'wechatuser')
        {
            $this->app->loadClass('wechatapi', true);
            $wechatApi  = new wechatapi($webhook->secret->appKey, $webhook->secret->appSecret, $webhook->secret->agentId);
            $response = $wechatApi->getAllUsers();
        }

        if($response['result'] == 'fail')
        {
            if($response['message'] == 'nodept')
            {
                echo js::error($this->lang->webhook->error->noDept);
                die(js::locate($this->createLink('webhook', 'chooseDept', "id=$id")));
            }

            echo js::error($response['message']);
            die(js::locate($this->createLink('webhook', 'browse')));
        }

        $dingUsers   = $response['data'];
        $bindedPairs = $this->webhook->getBoundUsers($id);
        $useridPairs = array('' => '');
        foreach($dingUsers as $name => $userid) $useridPairs[$userid] = $name;

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $users = $this->loadModel('user')->getByQuery($query = '', $pager);

        $unbindUsers = array();
        $bindedUsers = array();
        foreach($users as $user)
        {
            if(isset($bindedPairs[$user->account])) $bindedUsers[$user->account] = $user;
            if(!isset($bindedPairs[$user->account])) $unbindUsers[$user->account] = $user;
        }
        $users = $unbindUsers + $bindedUsers;

        $this->view->title      = $this->lang->webhook->bind;
        $this->view->position[] = html::a($this->createLink('webhook', 'browse'), $this->lang->webhook->common);
        $this->view->position[] = $this->lang->webhook->bind;

        $this->view->webhook       = $webhook;
        $this->view->dingUsers     = $dingUsers;
        $this->view->useridPairs   = $useridPairs;
        $this->view->users         = $users;
        $this->view->pager         = $pager;
        $this->view->bindedUsers   = $bindedPairs;
        $this->view->selectedDepts = $selectedDepts;
        $this->display();
    }

    /**
     * choose dept.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function chooseDept($id)
    {
        $webhook = $this->webhook->getById($id);
        if($webhook->type != 'dinguser' && $webhook->type != 'wechatuser')
        {
            echo js::alert($this->lang->webhook->note->bind);
            die(js::locate($this->createLink('webhook', 'browse')));
        }
        $webhook->secret = json_decode($webhook->secret);

        if($webhook->type == 'dinguser')
        {
            $this->app->loadClass('dingapi', true);
            $dingapi  = new dingapi($webhook->secret->appKey, $webhook->secret->appSecret, $webhook->secret->agentId);
            $response = $dingapi->getDeptTree();
        }

        if($response['result'] == 'fail')
        {
            echo js::error($response['message']);
            die(js::locate($this->createLink('webhook', 'browse')));
        }

        $this->view->title      = $this->lang->webhook->chooseDept;
        $this->view->position[] = $this->lang->webhook->chooseDept;

        $this->view->deptTree  = $response['data'];
        $this->view->webhookID = $id;
        $this->display();
    }

    /**
     * Send data by async. 
     * 
     * @access public
     * @return void
     */
    public function asyncSend()
    {
        $webhooks = $this->webhook->getList($orderBy = 'id_desc', $pager = null, $decode = false);
        if(empty($webhooks)) 
        {
            echo "NO WEBHOOK EXIST.\n";
            return false;
        }

        $dataList = $this->webhook->getDataList();
        if(empty($dataList)) 
        {
            echo "OK\n";
            return true;
        }

        $now  = helper::now();
        $diff = 0;
        foreach($dataList as $data)
        {
            $webhook = zget($webhooks, $data->objectID, '');
            if($webhook)
            {
                /* if connect time is out then ignore it.*/
                if($diff < 29)
                {
                    $time = time();
                    $result = $this->webhook->fetchHook($webhook, $data->data, $data->action);
                    $diff = time() - $time;
                }
                $this->webhook->saveLog($webhook, $data->action, $data->data, $result);
            }
            
            $this->dao->update(TABLE_NOTIFY)->set('status')->eq('sended')->set('sendTime')->eq($now)->where('id')->eq($data->id)->exec();
        }

        $this->dao->delete()->from(TABLE_NOTIFY)->where('status')->eq('sended')->exec();

        echo "OK\n";
        return true;
    }
}
