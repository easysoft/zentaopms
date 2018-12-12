<?php
$sn = $this->loadModel('setting')->getItem('owner=system&module=common&section=xuanxuan&key=key');
if(empty($sn))
{
    $this->setting->setItem('system.common.xuanxuan.turnon', 1);
    $this->setting->setItem('system.common.xuanxuan.key', $this->setting->computeSN());
}
