<?php
public function getRemind(): string
{
    $remind = parent::getRemind();

    $this->app->loadLang('im');
    $account     = str_replace('.', '_', $this->app->user->account);
    $xxInstalled = $account . 'installed';
    $module      = $this->app->getModuleName();

    if(isset($this->config->xxserver->installed) and $this->config->xuanxuan->turnon and !isset($this->config->xxclient->$xxInstalled) and $this->config->global->flow == 'full')
    {
        $remind .= '<h4>' . $this->lang->im->zentaoClient . '</h4>';
        $remind .= '<p>' . $this->lang->im->xxClientConfirm . '</p>';
        $this->loadModel('setting')->setItem("system.common.xxclient.{$account}installed", 1);
    }
    elseif(!isset($this->config->xxserver->noticed) and $this->app->user->admin and $this->config->global->flow == 'full' and isset($this->config->$module->block->initVersion) and $this->config->$module->block->initVersion >= '2')
    {
        $remind .= '<h4>' . $this->lang->im->zentaoClient . '</h4>';
        $remind .= '<p>' . $this->lang->im->xxServerConfirm . '</p>';
        $this->loadModel('setting')->setItem("system.common.xxserver.noticed", 1);
    }

    return $remind;
}
