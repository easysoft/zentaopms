#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::checkTokenAccess();
timeout=0
cid=1

- 不存在的服务器 @0
- 错误的服务器 @0
- 正确的服务器 @https://gogsdev.qc.oop.cc/api/v1%s?token=0c37d25758930f24e955dd0307bd37e975e3b457

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('pipeline')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(1);
su('admin');

global $tester;
$gogs = $tester->loadModel('gogs');

$failID   = 10;
$gitlabID = 1;
$gogsID   = 5;

r($gogs->getApiRoot($failID))   && p() && e('0'); // 不存在的服务器
r($gogs->getApiRoot($gitlabID)) && p() && e('0'); // 错误的服务器

r($gogs->getApiRoot($gogsID)) && p() && e('https://gogsdev.qc.oop.cc/api/v1%s?token=0c37d25758930f24e955dd0307bd37e975e3b457'); // 正确的服务器