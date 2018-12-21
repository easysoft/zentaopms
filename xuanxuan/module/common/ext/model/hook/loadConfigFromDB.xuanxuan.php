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
if(empty($xxConfig['chatPort']))       $this->setting->setItem('system.common.xuanxuan.chatPort', 11444);
if(empty($xxConfig['commonPort']))     $this->setting->setItem('system.common.xuanxuan.commonPort', 11443);
if(empty($xxConfig['ip']))             $this->setting->setItem('system.common.xuanxuan.ip', '0.0.0.0');
if(empty($xxConfig['isHttps']))        $this->setting->setItem('system.common.xuanxuan.isHttps', 0);
if(empty($xxConfig['uploadFileSize'])) $this->setting->setItem('system.common.xuanxuan.uploadFileSize', 20);
