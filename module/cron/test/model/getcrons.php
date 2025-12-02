#!/usr/bin/env php
<?php

/**

title=测试 cronModel->getCrons();
timeout=0
cid=15882

- 没有筛选条件时候，获取所有定时任务 @21
- 有筛选条件时候，获取定时任务 @18
- 获取某个定时任务的信息
 - 第12条的remark属性 @创建周期性任务
 - 第12条的command属性 @moduleName=ci&methodName=initQueue
- 获取正常列表某个定时任务的信息第1条的remark属性 @监控定时任务

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cron.unittest.class.php';
su('admin');

$cron = new cronTest();
$cron->init();

$list       = $cron->getCronsTest();
$noStopList = $cron->getCronsTest('nostop');

r(count($list))       && p()                    && e('21');                                                // 没有筛选条件时候，获取所有定时任务
r(count($noStopList)) && p()                    && e('18');                                                // 有筛选条件时候，获取定时任务
r($list)              && p('12:remark,command') && e('创建周期性任务,moduleName=ci&methodName=initQueue'); // 获取某个定时任务的信息
r($noStopList)        && p('1:remark')          && e('监控定时任务');                                      // 获取正常列表某个定时任务的信息
