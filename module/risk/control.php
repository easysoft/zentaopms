<?php 
class risk extends control
{
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title       = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->browse;
        $this->view->position[]  = $this->lang->risk->browse;
        $this->view->risks       = $this->risk->getList($orderBy, $pager);
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

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

        $this->view->users = $this->loadModel('user')->getPairs();
        $this->view->title       = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->create;
        $this->view->position[]  = $this->lang->risk->create;

        $this->display();
    }

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
            $actionID = $this->action->create('risk', $riskID, 'Edited');
            $this->action->logHistory($actionID, $changes);

            $response['locate']  = inlink('browse');
            $this->send($response);
        }

        $this->view->risk  = $this->risk->getByID($riskID);
        $this->view->users = $this->loadModel('user')->getPairs();
        $this->view->title       = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->edit;
        $this->view->position[]  = $this->lang->risk->edit;

        $this->display();
    }

    public function track($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->track($riskID);
        
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action');
            $actionID = $this->action->create('risk', $riskID, 'Tracked' . $this->post->commnet);
            $this->action->logHistory($actionID, $changes);

            $response['locate']  = inlink('browse');
            $this->send($response);
        }
        $this->view->risk       = $this->risk->getByID($riskID);
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->view->title      = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->track;
        $this->view->position[] = $this->lang->risk->track;

        $this->display();
    }

    public function assignTo($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->assign($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('risk', $riskID, 'Assigned', $this->post->comment, $this->post->assignedTo);            
            $this->action->logHistory($actionID, $changes);
            
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }
        $risk = $this->risk->getByID($riskID);

        $this->view->users = $this->loadModel('user')->getPairs();
        $this->view->title = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->assignedTo;
        $this->view->risk  = $risk;

        $this->display();
    }


    public function cancel($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->cancel($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('risk', $riskID, 'Canceled', $this->post->comment);            
            $this->action->logHistory($actionID, $changes);
            
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }
        $risk = $this->risk->getByID($riskID);

        $this->view->users = $this->loadModel('user')->getPairs();
        $this->view->title = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->cancel;
        $this->view->risk  = $risk;

        $this->display();
    }

    public function close($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->close($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('risk', $riskID, 'Closed', $this->post->comment);            
            $this->action->logHistory($actionID, $changes);
            
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }
        $risk = $this->risk->getByID($riskID);

        $this->view->users = $this->loadModel('user')->getPairs();
        $this->view->title = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->close;
        $this->view->risk  = $risk;

        $this->display();
    }

    public function hangup($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->hangup($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('risk', $riskID, 'Hangup', $this->post->comment);            
            $this->action->logHistory($actionID, $changes);
            
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }
        $risk = $this->risk->getByID($riskID);

        $this->view->users = $this->loadModel('user')->getPairs();
        $this->view->title = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->hangup;
        $this->view->risk  = $risk;

        $this->display();
    }

    public function activate($riskID)
    {
        if($_POST)
        {
            $changes = $this->risk->activate($riskID);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('risk', $riskID, 'Hangup', $this->post->comment);            
            $this->action->logHistory($actionID, $changes);
            
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('risk', 'browse'), 'parent'));
        }
        $risk = $this->risk->getByID($riskID);

        $this->view->users = $this->loadModel('user')->getPairs();
        $this->view->title = $this->lang->risk->common . $this->lang->colon . $this->lang->risk->activate;
        $this->view->risk  = $risk;

        $this->display();
    }
}
