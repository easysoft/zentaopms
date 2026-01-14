#!/usr/bin/env php
<?php

/**

title=测试 cneModel->getAppLogs();
timeout=0
cid=15612

- 获取CNE平台的日志 status @0
- 获取CNE平台的日志 status @0
- 获取CNE平台的日志 status @0
- 获取CNE平台的日志 status @0
- 获取CNE平台的日志 status @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$cneModel = new cneModelTest();

r($cneModel->getAppLogsTest()) && p('status') && e('0');

r($cneModel->getAppLogsTest()) && p('status') && e('0');

r($cneModel->getAppLogsTest()) && p('status') && e('0');

r($cneModel->getAppLogsTest()) && p('status') && e('0');

r($cneModel->getAppLogsTest()) && p('status') && e('0');