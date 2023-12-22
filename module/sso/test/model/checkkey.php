#!/usr/bin/env php
<?php

/**

title=ssoModel->checkKey();
cid=0

- 不存在config->sso->turnon配置 @0
- 不存在config->sso->key @0
- hash和key不匹配 @0
- hash和key相等 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$ssoModel = $tester->loadModel('sso');

r($ssoModel->checkKey()) && p() && e('0'); //不存在config->sso->turnon配置

$ssoModel->config->sso->turnon = true;
r($ssoModel->checkKey()) && p() && e('0'); //不存在config->sso->key

$ssoModel->config->sso->key = 'test';
$_GET['hash'] = '1234';
r($ssoModel->checkKey()) && p() && e('0'); //hash和key不匹配

$_GET['hash'] = 'test';
r($ssoModel->checkKey()) && p() && e('1'); //hash和key相等
