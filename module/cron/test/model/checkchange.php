#!/usr/bin/env php
<?php

/**

title=测试 cronModel->checkChange();
timeout=0
cid=1

- 判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在 @0
- 修改定时任务后，判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cron.unittest.class.php';
su('admin');

global $app;
include($app->getModuleRoot() . '/cron/control.php');
$app->control = new cron();

$cron = new cronTest();
$cron->init();

r($cron->checkChangeTest()) && p() && e('1'); //判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在
