<?php
class design extends control
{
    public function create($productID = 0, $prevModule = '', $prevID = 0)
    {
        $this->design->setProductMenu($productID);

        if($_POST)
        {
            $productID = $_POST['product'];

            $designID = $this->design->create();
            if($designID)
            {
                $this->loadModel('action')->create('design', $designID, 'create');

                $response['result']  = 'success';
                $response['message'] = $this->lang->saveSuccess;
                $response['locate']  = $this->createLink('design', 'browse', "productID=$productID");
                $this->send($response);
            }

            $response['result']  = 'fail';
            $response['message'] = dao::getError();
            $this->send($response);
        }

        $this->view->title      = $this->lang->design->create;
        $this->view->position[] = $this->lang->design->create;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->stories    = $this->loadModel('story')->getProductStoryPairs($productID);
        $this->view->products   = $this->loadModel('product')->getPairs($this->session->program);
        $this->view->productID  = $productID;
        $this->view->program    = $this->loadModel('project')->getByID($this->session->program);
        $this->display();
    }
}
