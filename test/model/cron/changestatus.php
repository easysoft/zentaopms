#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->changeStatus();
cid=1
pid=1

更新定时任务状态之后查看返回值 >> 1
更新定时任务状态之后查看状态值 >> stop

*/

$cron     = new cronTest();
$cronID   = 1;
$status   = 'stop';
$result   = $cron->changeStatusTest($cronID, $status);
$cronInfo = $tester->cron->getById($cronID);

r($result)   && p()         && e('1');    //更新定时任务状态之后查看返回值
r($cronInfo) && p('status') && e('stop'); //更新定时任务状态之后查看状态值

