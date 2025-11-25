#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

/**

title=taskModel->checkEffort();
timeout=0
cid=18866

- 检查正常编辑的工时信息，查看返回的信息 @1

- 检查无剩余工时的工时信息，查看返回的信息 @1

- 编辑日志消耗为0，查看返回的信息 @『工时』应当大于『0』。

- 编辑日志日期为空，查看返回的信息 @请填写"日期"

- 编辑日志日期大于今天，查看返回的信息 @日期不能大于今天

*/
$effort = zenData('effort');
$effort->objectType->range('task');
$effort->objectID->range('1-5');
$effort->execution->range('1-5');
$effort->date->range('2022\-01\-01');
$effort->work->prefix('工作内容')->range('1-10');
$effort->consumed->range('1-10');
$effort->left->range('1-10');
$effort->gen(10);

$normalEffort = new stdclass();
$normalEffort->consumed = 2.1;
$normalEffort->left     = 1.2;
$normalEffort->work     = '正常变更工作内容测试';
$normalEffort->date     = helper::today();

$noLeftEffort = new stdclass();
$noLeftEffort->consumed = 2.1;
$noLeftEffort->left     = 0;
$noLeftEffort->work     = '无剩时间余变更工作内容测试';
$noLeftEffort->date     = helper::today();

$noConsumedEffort = new stdclass();
$noConsumedEffort->consumed = 0;
$noConsumedEffort->left     = 3;
$noConsumedEffort->work     = '无消耗变更工作内容测试';
$noConsumedEffort->date     = helper::today();

$noDateEffort = new stdclass();
$noDateEffort->consumed = 1;
$noDateEffort->left     = 3;
$noDateEffort->work     = '无消耗变更工作内容测试';
$noDateEffort->date     = '0000-00-00';

$dateGtTodayEffort = new stdclass();
$dateGtTodayEffort->consumed = 1;
$dateGtTodayEffort->left     = 3;
$dateGtTodayEffort->work     = '无消耗变更工作内容测试';
$dateGtTodayEffort->date     = date('Y-m-d', strtotime('+1 day'));

$task = new taskTest();
r($task->checkEffortTest(1, $normalEffort))      && p()           && e('1');                       // 检查正常编辑的工时信息，查看返回的信息
r($task->checkEffortTest(2, $noLeftEffort))      && p()           && e('1');                       // 检查无剩余工时的工时信息，查看返回的信息
r($task->checkEffortTest(3, $noConsumedEffort))  && p('comsumed') && e('『工时』应当大于『0』。'); // 编辑日志消耗为0，查看返回的信息
r($task->checkEffortTest(4, $noDateEffort))      && p('date')     && e('请填写"日期"');            // 编辑日志日期为空，查看返回的信息
r($task->checkEffortTest(5, $dateGtTodayEffort)) && p('date')     && e('日期不能大于今天');        // 编辑日志日期大于今天，查看返回的信息
