#!/usr/bin/env php
<?php

/**

title=测试 cronModel->checkChange();
timeout=0
cid=1

- 判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在 @1
- 修改lastTime字段，再次判断 @0
- 删除所有定时任务，再次判断 @0

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

$cron->objectModel->dao->update(TABLE_CRON)->set('lastTime')->eq(helper::now())->exec();
r($cron->checkChangeTest()) && p() && e('0'); //修改lastTime字段，再次判断

$cron->objectModel->dao->delete()->from(TABLE_CRON)->exec();
r($cron->checkChangeTest()) && p() && e('0'); //删除所有定时任务，再次判断
