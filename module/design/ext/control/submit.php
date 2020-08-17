<?php
class design extends control
{
    public function submit($productID = 0, $designID = 0, $allLabel = 0, $designType = 'all')
    {
        if($_POST)
        {
            $object      = $this->post->object;
            $reviewRange = $this->post->range;
            $designID    = $this->post->designID;
            $product     = $this->loadModel('product')->getByID($productID);
            $programID   = isset($product->project) ? $product->project : $this->session->program;
            $reviewRange == 'all' ? $checkedItem = '' : $checkedItem = $designID;

            if($reviewRange == 'assign' && empty($checkedItem)) die(js::alert($this->lang->design->errorSelection));

            die(js::locate($this->createLink('review', 'create', "program={$programID}&object=$object&productID=$productID&reviewRange=$reviewRange&checkedItem={$checkedItem}"), 'parent'));
        }

        if($allLabel) unset($this->lang->design->rangeList['assign']);
        $this->view->designType = $designType;
        $this->display();
    }
}
