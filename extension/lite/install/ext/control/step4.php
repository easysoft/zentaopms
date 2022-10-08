<?php
helper::importControl('install');
class myinstall extends install
{
    public function step4()
    {
        if(!isset($this->config->installed) or !$this->config->installed)
        {
            $this->view->error = $this->lang->install->errorNotSaveConfig;
            $this->display();
        }

        $_POST['mode'] = 'new';
        return parent::step4();
    }
}
