<?php
helper::importControl('install');
class myinstall extends install
{
    public function index()
    {
        $this->view->versionName = $this->config->liteVersion;
        return parent::index();
    }
}
