<?php
$this->loadModel('setting');
$xxItems  = $this->setting->getItems('owner=system&module=common&section=xuanxuan');
$xxConfig = array();
foreach($xxItems as $xxItem) $xxConfig[$xxItem->key] = $xxItem->value;
if(empty($xxConfig['key']))
{
    $this->setting->setItem('system.common.xuanxuan.turnon', 1);
    $this->setting->setItem('system.common.xuanxuan.key', $this->setting->computeSN());
}
if(!isset($xxConfig['chatPort']))       $this->setting->setItem('system.common.xuanxuan.chatPort', 11444);
if(!isset($xxConfig['commonPort']))     $this->setting->setItem('system.common.xuanxuan.commonPort', 11443);
if(!isset($xxConfig['ip']))             $this->setting->setItem('system.common.xuanxuan.ip', '0.0.0.0');
if(!isset($xxConfig['isHttps']))        $this->setting->setItem('system.common.xuanxuan.isHttps', 'off');
if(!isset($xxConfig['uploadFileSize'])) $this->setting->setItem('system.common.xuanxuan.uploadFileSize', 20);
