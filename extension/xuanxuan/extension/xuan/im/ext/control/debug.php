<?php
helper::import('../../control.php');
class myIm extends im
{
    /**
     * Debug xuanxuan.
     *
     * @access public
     * @return void
     */
    public function debug($source = 'x_php')
    {
        $this->view->title          = $this->lang->im->debug;
        $this->view->source         = $source;
        $this->view->xxdStatus      = $this->im->getXxdStatus();
        $this->view->checkXXBConfig = $this->im->checkXXBConfig();
        $this->view->domain         = $this->im->getServer('zentao');
        $this->display();
    }
}
