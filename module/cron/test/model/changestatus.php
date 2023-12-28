#!/usr/bin/env php
<?php

/**

title=测试 cronModel->changeStatus();
timeout=0
cid=1

- 更新定时任务状态之后查看返回值 @1
- 更新定时任务状态之后查看状态值属性status @stop

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron     = new cronTest();
$cronID   = 1;
$status   = 'stop';
$result   = $cron->changeStatusTest($cronID, $status);
$cronInfo = $tester->cron->getById($cronID);

r($result)   && p()         && e('1');    //更新定时任务状态之后查看返回值
r($cronInfo) && p('status') && e('stop'); //更新定时任务状态之后查看状态值