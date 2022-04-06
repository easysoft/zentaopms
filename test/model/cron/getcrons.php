#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->getCrons();
cid=1
pid=1

没有筛选条件时候，获取所有定时任务 >> 19
有筛选条件时候，获取定时任务 >> 16
获取某个定时任务的信息 >> 同步DevOps构建任务状态,moduleName=ci&methodName=checkCompileStatus

*/

$cron       = new cronTest();
$list       = $cron->getCronsTest();
$noStopList = $cron->getCronsTest('nostop');

r(count($list))       && p()                    && e('19');                                                                 // 没有筛选条件时候，获取所有定时任务
r(count($noStopList)) && p()                    && e('16');                                                                 // 有筛选条件时候，获取定时任务
r($list)              && p('12:remark,command') && e('同步DevOps构建任务状态,moduleName=ci&methodName=checkCompileStatus'); // 获取某个定时任务的信息