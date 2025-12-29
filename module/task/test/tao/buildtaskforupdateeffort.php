#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

/**

title=taskModel->buildTaskForUpdateEffort();
timeout=0
cid=18863

- 正常编辑的工时信息，查看返回的任务信息
 - 属性consumed @4.1
 - 属性left @6.00
 - 属性status @wait
- 无剩余工时的工时信息，查看返回的任务信息
 - 属性consumed @4.1
 - 属性left @7.00
 - 属性status @wait
- 编辑日志消耗为0，查看返回的任务信息
 - 属性consumed @2
 - 属性left @8.00
 - 属性status @doing
- 编辑日志日期为空，查看返回的任务信息
 - 属性consumed @3
 - 属性left @9.00
 - 属性status @done
- 编辑日志日期大于今天，查看返回的任务信息
 - 属性consumed @3
 - 属性left @10.00
 - 属性status @pause

*/

$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('1-10');
$task->name->prefix("任务")->range('1-10');
$task->left->range('1-10');
$task->mode->range(" , multi, , , , ,");
$task->estStarted->range('2022\-01\-01');
$task->assignedTo->prefix("user")->range('1-10');
$task->status->range("wait,wait,doing,done,pause,cancel,closed");
$task->gen(10);

$taskteam = zenData('taskteam');
$taskteam->id->range('1-5');
$taskteam->task->range('2');
$taskteam->account->prefix("user")->range('1-5');
$taskteam->estimate->range('5');
$taskteam->consumed->range('0');
$taskteam->left->range('5');
$taskteam->status->range("wait");
$taskteam->gen(5);

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
r($task->buildTaskForUpdateEffortTest(1, 1, $normalEffort))      && p('consumed,left,status') && e('4.1,6.00,wait'); // 正常编辑的工时信息，查看返回的任务信息
r($task->buildTaskForUpdateEffortTest(2, 2, $noLeftEffort))      && p('consumed,left,status') && e('4.1,7.00,wait'); // 无剩余工时的工时信息，查看返回的任务信息
r($task->buildTaskForUpdateEffortTest(3, 3, $noConsumedEffort))  && p('consumed,left,status') && e('2,8.00,doing');  // 编辑日志消耗为0，查看返回的任务信息
r($task->buildTaskForUpdateEffortTest(4, 4, $noDateEffort))      && p('consumed,left,status') && e('3,9.00,done');   // 编辑日志日期为空，查看返回的任务信息
r($task->buildTaskForUpdateEffortTest(5, 5, $dateGtTodayEffort)) && p('consumed,left,status') && e('3,10.00,pause'); // 编辑日志日期大于今天，查看返回的任务信息
