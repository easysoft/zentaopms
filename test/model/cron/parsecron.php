#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->parseCron();
cid=1
pid=1

获取id为19的定时任务的定时信息和命令信息 >> moduleName=measurement&methodName=execCrontabQueue
获取id为15的定时任务的定时信息和命令信息 >> moduleName=execution&methodName=computeTaskEffort

*/

$cron  = new cronTest();
$crons = $tester->cron->getCrons('nostop');
$parse = $cron->parseCronTest($crons);

r($parse) && p('19:command') && e('moduleName=measurement&methodName=execCrontabQueue');//获取id为19的定时任务的定时信息和命令信息
r($parse) && p('15:command') && e('moduleName=execution&methodName=computeTaskEffort'); //获取id为15的定时任务的定时信息和命令信息

