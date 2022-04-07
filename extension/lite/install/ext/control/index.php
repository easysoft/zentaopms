<?php
helper::importControl('install');
class myinstall extends install
{
    /**
     * Index page of install module.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        if(!isset($this->config->installed) or !$this->config->installed) $this->session->set('installing', true);

        $this->view->title = $this->lang->install->welcome;
        if(!isset($this->view->versionName))
        {
            $versionName = ucfirst($this->config->vision);
            if($this->config->edition == 'biz') $versionName .= 'VIP';
            $versionName .= $this->config->liteVersion; // If the versionName variable has been defined in the max version, it cannot be defined here to avoid being overwritten.
            $this->view->versionName = $versionName;
        }
        $this->display();
    }
}
