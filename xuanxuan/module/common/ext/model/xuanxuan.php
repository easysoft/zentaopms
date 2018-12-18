<?php
public function loadConfigFromDB()
{
    parent::loadConfigFromDB();
    if(isset($this->config->system->xuanxuan)) $this->app->mergeConfig($this->config->system->xuanxuan, 'xuanxuan');
}
