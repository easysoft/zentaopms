<?php
class chat extends control
{
    public function extensions($userID = 0)
    {
        $this->output->result = 'success';
        $this->output->data   = array();
        $this->output->users  = array($userID);
        die($this->app->encrypt($this->output));
    }
}
