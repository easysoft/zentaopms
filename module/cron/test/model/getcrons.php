#!/usr/bin/env php
<?php

/**

title=测试 cronModel->getCrons();
timeout=0
cid=1

- 没有筛选条件时候，获取所有定时任务 @17
- 有筛选条件时候，获取定时任务 @14
- 获取某个定时任务的信息
 - 第12条的remark属性 @同步DevOps构建任务状态
 - 第12条的command属性 @moduleName=ci&methodName=checkCompileStatus

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron = new cronTest();
$cron->init();

$list       = $cron->getCronsTest();
$noStopList = $cron->getCronsTest('nostop');

r(count($list))       && p()                    && e('17');                                                                 // 没有筛选条件时候，获取所有定时任务
r(count($noStopList)) && p()                    && e('14');                                                                 // 有筛选条件时候，获取定时任务
r($list)              && p('12:remark,command') && e('同步DevOps构建任务状态,moduleName=ci&methodName=checkCompileStatus'); // 获取某个定时任务的信息