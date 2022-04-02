#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->parseCron();
cid=1
pid=1

*/

$cron  = new cronTest();
$crons = $tester->cron->getCrons('nostop');
$parse = $cron->parseCronTest($crons);

r($parse) && p('19:schema,command') && e('*/5 * * * *,moduleName=measurement&methodName=execCrontabQueue');//获取id为19的定时任务的定时信息和命令信息
r($parse) && p('15:schema,command') && e('30 23 * * *,moduleName=execution&methodName=computeTaskEffort'); //获取id为15的定时任务的定时信息和命令信息

system('./ztest init');
