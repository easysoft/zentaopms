<?php
/**
 * The control file of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id: control.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class issue extends control
{
    /**
     * Get issue list data.
     *
     * @param  string $browseType
     * @param  string orderBy
     * @param  int recTotal
     * @param  int recPerPage
     * @param  int pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);
        /* Load pager */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->browse;
        $this->view->position[] = $this->lang->issue->browse;

        $this->view->pager      = $pager;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->issueList  = $this->issue->getIssueList($browseType, $orderBy, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');

        $this->display();
    }

    /**
     * Create a issue.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $issueID = $this->issue->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('issue', $issueID, 'Opened');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', 'browseType=all')));
        }

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->create;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->create;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

   /**
    * batchCreate issues
    *
    * @access public
    * @return void
    */ 
    public function batchCreate()
    {
        if($_POST)
        {
            $results = $this->issue->batchCreate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', 'browseType=all')));
        }

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->batchCreate;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->batchCreate;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * Delete a issue.
     *
     * @param  int    $issueID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($issueID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->issue->confirmDelete, inLink('delete', "issueID=$issueID&confirm=yes")));
        }
        else
        {
            $this->issue->delete($issueID);
            die(js::reload('parent'));
        }
    }

    /**
     * Edit a issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function edit($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->update($issueID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', 'browseType=all')));
        }

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->edit;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->edit;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->issue = $this->issue->getByID($issueID);

        $this->display();
    }

    /**
     * Assign a issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function assignTo($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->assignTo($issueID);

            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Edited');

            $this->action->logHistory($actionID, $changes);
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->issue = $this->issue->getByID($issueID);
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * Close a issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function close($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->close($issueID);

            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Edited');

            $this->action->logHistory($actionID, $changes);
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->issue = $this->issue->getByID($issueID);

        $this->display();
    }

    /**
     * Cancel a issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function cancel($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->cancel($issueID);

            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Edited');

            $this->action->logHistory($actionID, $changes);
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->issue = $this->issue->getByID($issueID);

        $this->display();
    }

    /**
     * Activate a issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function activate($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->activate($issueID);

            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Edited');

            $this->action->logHistory($actionID, $changes);
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->issue = $this->issue->getByID($issueID);
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * Resolve a issue.
     *
     * @param  int    $issue
     * @access public
     * @return void
     */
    public function  resolve($issue)
    {
        if($_POST)
        {
            $this->issue->resolve($issue);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->issue->resolved;
        $this->view->issue = $this->issue->getByID($issue);
        $this->view->users = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     *  Get question details.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function view($issueID)
    {
        $this->commonAction($issueID, 'issue');
        $issue = $this->issue->getByID($issueID);
        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $issue->title;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->basicInfo;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->issue = $issue;

        $this->display();
    }

    /** 
     * Common actions of issue module.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function commonAction($issueID, $object)
    {
        $this->view->actions = $this->loadModel('action')->getList($object, $issueID);
    }
}
