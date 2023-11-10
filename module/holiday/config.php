<?php
if(!isset($config->holiday)) $config->holiday = new stdclass();
$config->holiday->require = new stdclass();
$config->holiday->require->create = 'name,begin,end';
$config->holiday->require->edit   = 'name,begin,end';

if(!isset($config->ghproxy)) $config->ghproxy = 'https://ghproxy.com/';
$config->holiday->apiRoot = $config->ghproxy . 'https://raw.githubusercontent.com/NateScarlet/holiday-cn/master/%d.json';
