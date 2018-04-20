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
            $this->send(array('result' => 'success', 'message' => $this->lang->webhook->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->webhook->api . $this->lang->colon . $this->lang->webhook->create;
        $this->view->products      = $this->loadModel('product')->getPairs();
        $this->view->projects      = $this->loadModel('project')->getPairs();
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->webhook->api);
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->webhook->common);
        $this->view->position[]    = $this->lang->webhook->create;
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
        $webhook = $this->webhook->getByID($id);
        if($_POST)
        {
            $this->webhook->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->webhook->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->webhook->edit . $this->lang->colon . $webhook->name;
        $this->view->products      = $this->loadModel('product')->getPairs();
        $this->view->projects      = $this->loadModel('project')->getPairs();
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->webhook->api);
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->webhook->common);
        $this->view->position[]    = $this->lang->webhook->edit;
        $this->view->webhook       = $webhook;
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

        $now = helper::now();
        foreach($dataList as $data)
        {
            $webhook = zget($webhooks, $data->objectID, '');
            if($webhook)
            {
                $result = $this->webhook->fetchHook($webhook, $data->data);
                $this->webhook->saveLog($webhook, $data->action, $data->data, $result);
            }
            
            $this->dao->update(TABLE_NOTIFY)->set('status')->eq('sended')->set('sendTime')->eq($now)->where('id')->eq($data->id)->exec();
        }

        $this->dao->delete()->from(TABLE_NOTIFY)->where('status')->eq('sended')->exec();

        echo "OK\n";
        return true;
    }
}
