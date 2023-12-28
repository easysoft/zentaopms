#!/usr/bin/env php
<?php

/**

title=测试 cronModel->parseCron();
timeout=0
cid=1

- 获取id为2的定时任务的定时信息和命令信息第2条的command属性 @moduleName=execution&methodName=computeburn
- 获取id为3的定时任务的定时信息和命令信息第3条的command属性 @moduleName=report&methodName=remind
- 获取id为1的定时任务的定时信息和命令信息第1条的command属性 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron = new cronTest();
$cron->init();

$crons = $tester->cron->getCrons('nostop');
$parse = $cron->parseCronTest($crons);

r($parse) && p('2:command') && e('moduleName=execution&methodName=computeburn'); //获取id为2的定时任务的定时信息和命令信息
r($parse) && p('3:command') && e('moduleName=report&methodName=remind');         //获取id为3的定时任务的定时信息和命令信息
r($parse) && p('1:command') && e('~~');                                          //获取id为1的定时任务的定时信息和命令信息