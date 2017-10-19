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
     * Browse entries. 
     * 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $pager = $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title   = $this->lang->webhook->api . $this->lang->colon . $this->lang->webhook->list;
        $this->view->entries = $this->webhook->getList($orderBy, $pager);
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;
        $this->display();
    }

    /**
     * Create an webhook. 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $webhookID = $this->webhook->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('webhook', $webhookID, 'created');
            $this->send(array('result' => 'success', 'message' => $this->lang->webhook->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->webhook->api . $this->lang->colon . $this->lang->webhook->create;
        $this->display();
    }

    /**
     * Edit an webhook. 
     * 
     * @param  int    $webhookID 
     * @access public
     * @return void
     */
    public function edit($webhookID)
    {
        if($_POST)
        {
            $changes = $this->webhook->update($webhookID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('webhook', $webhookID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->webhook->saveSuccess, 'locate' => inlink('browse')));
        }

        $webhook = $this->webhook->getById($webhookID);
        $this->view->title   = $this->lang->webhook->edit . $this->lang->colon . $webhook->name;
        $this->view->webhook = $webhook;
        $this->display();
    }

    /**
     * Delete an webhook. 
     * 
     * @param  int    $webhookID 
     * @access public
     * @return void
     */
    public function delete($webhookID)
    {
        $this->webhook->delete($webhookID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    public function log($webhookID)
    {
        $webhook = $this->webhook->getById($webhookID);
        $this->view->title   = $this->lang->webhook->log . $this->lang->colon . $webhook->name;
        $this->view->actions = $this->loadModel('action')->getList('webhook', $webhookID);
        $this->display();
    }

    public function actions($webhookID)
    {
        if($_POST)
        {
            $this->webhook->saveActions($webhookID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->webhook->saveSuccess, 'locate' => inlink('browse')));
        }

        $webhook = $this->webhook->getById($webhookID);
        $this->view->title   = $this->lang->webhook->actions . $this->lang->colon . $webhook->name;
        $this->view->actions = $this->webhook->getActions($webhookID);
        $this->display();
    }

    public function ajaxGetActions($module)
    {
    }
}
