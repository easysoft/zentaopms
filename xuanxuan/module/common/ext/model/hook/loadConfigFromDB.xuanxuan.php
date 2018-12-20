<?php
$sn             = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=key');
$chatPort       = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=chatPort');
$commonPort     = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=commonPort');
$ip             = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=ip');
$isHttps        = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=isHttps');
$uploadFileSize = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=uploadFileSize');
if(empty($sn))
{
    $this->setting->setItem('system.common.xuanxuan.turnon', 1);
    $this->setting->setItem('system.common.xuanxuan.key', $this->setting->computeSN());
}
if(empty($chatPort))       $this->setting->setItem('system.common.xuanxuan.chatPort', 11444);
if(empty($commonPort))     $this->setting->setItem('system.common.xuanxuan.commonPort', 11443);
if(empty($ip))             $this->setting->setItem('system.common.xuanxuan.ip', '0.0.0.0');
if(empty($isHttps))        $this->setting->setItem('system.common.xuanxuan.isHttps', 0);
if(empty($uploadFileSize)) $this->setting->setItem('system.common.xuanxuan.uploadFileSize', 20);
