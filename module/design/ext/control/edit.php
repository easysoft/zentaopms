<?php
class design extends control
{
    public function edit($designID = 0)
    {
        $design = $this->design->getByID($designID);
        $design = $this->design->getAffectedScope($design);
        $this->design->setProductMenu($design->product);

        if($_POST)
        {
            $changes = $this->design->update($designID);
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('design', $designID, 'edit');
                $this->action->logHistory($actionID, $changes);

            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $this->createLink('design', 'view', "id=$design->id");
            $this->send($response);
        }

        $this->view->title      = $this->lang->design->edit;
        $this->view->position[] = $this->lang->design->edit;

        $this->view->design   = $design;
        $this->view->products = $this->loadModel('product')->getPairs($this->session->program);
        $this->view->program  = $this->loadModel('project')->getByID($this->session->program);
        $this->view->stories  = $this->loadModel('story')->getProductStoryPairs($design->product);
        $this->display();
    }
}
