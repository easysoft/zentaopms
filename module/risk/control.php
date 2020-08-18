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
}
