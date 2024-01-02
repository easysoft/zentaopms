#!/usr/bin/env php
<?php

/**

title=测试 cneModel->batchQueryStatus();
timeout=0
cid=1

- 获取第一个应用的状态信息
 - 第gitlab-20231226133115条的status属性 @running
 - 第gitlab-20231226133115条的access_host属性 @jflc.dops.corp.cc
- 获取第二个应用的状态信息
 - 第subversion-20240102093246条的status属性 @running
 - 第subversion-20240102093246条的access_host属性 @rheu.dops.corp.cc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel = new cneTest();
$result = $cneModel->batchQueryStatusTest();
r($result) && p('gitlab-20231226133115:status,access_host')     && e('running,jflc.dops.corp.cc'); // 获取第一个应用的状态信息
r($result) && p('subversion-20240102093246:status,access_host') && e('running,rheu.dops.corp.cc'); // 获取第二个应用的状态信息