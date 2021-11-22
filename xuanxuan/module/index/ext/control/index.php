<?php
include '../../control.php';
class myIndex extends index
{
    /**
     * The index page of whole zentao system.
     *
     * @param  string $open
     * @access public
     * @return void
     */
    public function index($open = '')
    {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false)
        {
            $this->view->pageBodyClass = 'xxc-embed';
        }

        return parent::index($open);
    }
}
