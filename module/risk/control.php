<?php 
/**
 * The control file of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: control.php 5107 2020-09-04 09:06:12Z lyc $
 * @link        http://www.zentao.net
 */
class risk extends control
{
    /**
     * Browse risks.
     *
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('riskList',  $uri);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('risk', 'browse', "browseType=bysearch&queryID=myQueryID");
        $this->risk->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->browse;
        $this->view->position[] = $this->lang->risk->browse;
        $this->view->risks      = $this->risk->getList($this->session->PRJ, $browseType, $param, $orderBy, $pager);
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Create a risk.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $riskID = $this->risk->create();

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(!$riskID)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('risk', $riskID, 'Opened');
            $response['locate']  = inlink('browse');
            $this->send($response);
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->create;
        $this->view->position[] = $this->lang->risk->create;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed');
        $this->display();
    }

    /**
     * Edit a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function edit($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->update($riskID);
        
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action');
            if(!empty($changes)) 
            {
                $actionID = $this->action->create('risk', $riskID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['locate'] = inlink('browse');
            $this->send($response);
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->edit;
        $this->view->position[] = $this->lang->risk->edit;

        $this->view->risk  = $this->risk->getById($riskID);
        $this->view->users = $this->loadModel('user')->getPairs('noclosed');
        $this->display();
    }

    /**
     * View a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function view($riskID)
    {
        $this->loadModel('action');

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->view;
        $this->view->position[] = $this->lang->risk->view;

        $this->view->risk    = $this->risk->getById($riskID);
        $this->view->actions = $this->action->getList('risk', $riskID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Batch create risks.
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        if($_POST)
        {
            $this->risk->batchCreate();

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $response['locate'] = inlink('browse');
            $this->send($response);
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->batchCreate;
        $this->view->position[] = $this->lang->risk->batchCreate;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed');
        $this->display();
    }

    /**
     * Delete a risk.
     *
     * @param  int    $riskID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($riskID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->risk->confirmDelete, $this->createLink('risk', 'delete', "risk=$riskID&confirm=yes"), ''));
        }
        else
        {
            $this->risk->delete(TABLE_RISK, $riskID);

            die(js::locate(inlink('browse'), 'parent'));
        }
    }

    /**
     * Track a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function track($riskID)
    {
        if($_POST)
        {
            $changes = array();
            if($this->post->isChange) $changes = $this->risk->track($riskID);
        
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action');
            if(!empty($changes) or $_POST['comment']) 
            {
                $actionID = $this->action->create('risk', $riskID, 'Tracked', $_POST['comment']);
                $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            $this->send(array('locate' => inlink('browse')));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->track;
        $this->view->position[] = $this->lang->risk->track;

        $this->view->risk  = $this->risk->getById($riskID);
        $this->view->users = $this->loadModel('user')->getPairs('noclosed');
        $this->display();
    }

    /**
     * Update assign of risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function assignTo($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->assign($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Assigned', $this->post->comment, $this->post->assignedTo);            
                $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->assignedTo;
        $this->view->position[] = $this->lang->risk->assignedTo;

        $this->view->risk  = $this->risk->getById($riskID);
        $this->view->users = $this->loadModel('user')->getPairs();
        $this->display();
    }


    /**
     * Cancel a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function cancel($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->cancel($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Canceled', $this->post->comment);            
                $this->action->logHistory($actionID, $changes);
            }
            
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->cancel;
        $this->view->position[] = $this->lang->risk->cancel;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed');
        $this->view->risk  = $this->risk->getById($riskID);
        $this->display();
    }

    /**
     * Close a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function close($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->close($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Closed', $this->post->comment);            
                $this->action->logHistory($actionID, $changes);
            }
            
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->close;
        $this->view->position[] = $this->lang->risk->close;

        $this->view->users = $this->loadModel('user')->getPairs();
        $this->view->risk  = $this->risk->getById($riskID);
        $this->display();
    }

    /**
     * Hangup a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function hangup($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->hangup($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Hangup', $this->post->comment);            
                $this->action->logHistory($actionID, $changes);
            }
            
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->hangup;
        $this->view->position[] = $this->lang->risk->hangup;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed');
        $this->view->risk  = $this->risk->getById($riskID);
        $this->display();
    }

    /**
     * Activate a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function activate($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->activate($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Activated', $this->post->comment);            
                $this->action->logHistory($actionID, $changes);
            }

            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->activate;
        $this->view->position[] = $this->lang->risk->activate;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed');
        $this->view->risk  = $this->risk->getById($riskID);
        $this->display();
    }

    /**
     * AJAX: return risks of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id
     * @param  string $status
     * @access public
     * @return void
     */
    public function ajaxGetUserRisks($userID = '', $id = '', $status = 'all')
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $risks = $this->risk->getUserRiskPairs($account, 0, $status);

        if($id) die(html::select("risks[$id]", $risks, '', 'class="form-control"'));
        die(html::select('risk', $risks, '', 'class=form-control'));
    }
}

