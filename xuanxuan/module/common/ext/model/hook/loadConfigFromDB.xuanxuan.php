<?php
$sn = $this->loadModel('setting')->getItem('owner=system&module=xuanxuan&key=key');
if(empty($sn)) $this->setting->setItem('system.xuanxuan..key', $this->setting->computeSN());
