<?php
$sn         = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=key');
$chatPort   = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=chatPort');
$commonPort = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=commonPort');
$ip         = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=ip');
$isHttps    = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=isHttps');
if(empty($sn))
{
    $this->setting->setItem('system.common.xuanxuan.turnon', 1);
    $this->setting->setItem('system.common.xuanxuan.key', $this->setting->computeSN());
}
if(!isset($chatPort))   $this->setting->setItem('system.common.xuanxuan.chatPort', 11444);
if(!isset($commonPort)) $this->setting->setItem('system.common.xuanxuan.commonPort', 11443);
if(!isset($ip))         $this->setting->setItem('system.common.xuanxuan.ip', '0.0.0.0');
if(!isset($isHttps))    $this->setting->setItem('system.common.xuanxuan.isHttps', 0);
