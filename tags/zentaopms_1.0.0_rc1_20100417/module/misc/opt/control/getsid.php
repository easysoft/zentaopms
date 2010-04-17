<?php

class misc extends control
{
    public function getsid()
    {
        $this->view->header->title = 'getsid';
        $this->view->sid  = session_id();
        $this->view->test = $this->misc->test();
        $this->display();
    }
}
?>
