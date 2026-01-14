#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');
/**

title=taskModel->updateEffort();
timeout=0
cid=18848

- 正常编辑日志，查看返回的信息
 - 第0条的field属性 @consumed
 - 第0条的old属性 @3.00
 - 第0条的new属性 @4.1
- 正常编辑日志，查看返回的信息
 - 第0条的field属性 @consumed
 - 第0条的old属性 @4.00
 - 第0条的new属性 @9.1
- 正常编辑日志，查看返回的信息
 - 第0条的field属性 @consumed
 - 第0条的old属性 @5.00
 - 第0条的new属性 @4.1
- 正常编辑日志，查看返回的信息
 - 第0条的field属性 @consumed
 - 第0条的old属性 @6.00
 - 第0条的new属性 @4.1
- 正常编辑日志，查看返回的信息
 - 第0条的field属性 @consumed
 - 第0条的old属性 @7.00
 - 第0条的new属性 @4.1
- 编辑日志，剩余时间传0，查看返回的信息 @0
- 编辑日志，剩余时间传0，查看返回的信息 @0
- 编辑日志，剩余时间传0，查看返回的信息 @0
- 编辑日志，剩余时间传0，查看返回的信息 @0
- 编辑日志，剩余时间传0，查看返回的信息 @0
- 编辑日志，消耗时间传0，查看返回的信息属性comsumed @『工时』应当大于『0』。
- 编辑日志，消耗时间传0，查看返回的信息属性comsumed @『工时』应当大于『0』。

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

$user = zenData('user');
$user->gen(20);

$normalEffort = new stdclass();
$normalEffort->consumed = 2.1;
$normalEffort->left     = 1.2;
$normalEffort->work     = '正常变更工作内容测试';

$noLeftEffort = new stdclass();
$noLeftEffort->consumed = 2.1;
$noLeftEffort->left     = 0;
$noLeftEffort->work     = '无剩时间余变更工作内容测试';

$noConsumedEffort = new stdclass();
$noConsumedEffort->consumed = 0;
$noConsumedEffort->left     = 3;
$noConsumedEffort->work     = '无剩时间余变更工作内容测试';

$task = new taskModelTest();
r($task->updateEffortTest(1, $normalEffort)) && p('0:field,old,new') && e('consumed,3.00,4.1'); // 正常编辑日志，查看返回的信息
r($task->updateEffortTest(2, $normalEffort)) && p('0:field,old,new') && e('consumed,4.00,9.1'); // 正常编辑日志，查看返回的信息
r($task->updateEffortTest(3, $normalEffort)) && p('0:field,old,new') && e('consumed,5.00,4.1'); // 正常编辑日志，查看返回的信息
r($task->updateEffortTest(4, $normalEffort)) && p('0:field,old,new') && e('consumed,6.00,4.1'); // 正常编辑日志，查看返回的信息
r($task->updateEffortTest(5, $normalEffort)) && p('0:field,old,new') && e('consumed,7.00,4.1'); // 正常编辑日志，查看返回的信息

r($task->updateEffortTest(1, $noLeftEffort)) && p() && e('0'); // 编辑日志，剩余时间传0，查看返回的信息
r($task->updateEffortTest(2, $noLeftEffort)) && p() && e('0'); // 编辑日志，剩余时间传0，查看返回的信息
r($task->updateEffortTest(3, $noLeftEffort)) && p() && e('0'); // 编辑日志，剩余时间传0，查看返回的信息
r($task->updateEffortTest(4, $noLeftEffort)) && p() && e('0'); // 编辑日志，剩余时间传0，查看返回的信息
r($task->updateEffortTest(5, $noLeftEffort)) && p() && e('0'); // 编辑日志，剩余时间传0，查看返回的信息

r($task->updateEffortTest(1, $noConsumedEffort)) && p('comsumed') && e('『工时』应当大于『0』。'); // 编辑日志，消耗时间传0，查看返回的信息
r($task->updateEffortTest(2, $noConsumedEffort)) && p('comsumed') && e('『工时』应当大于『0』。'); // 编辑日志，消耗时间传0，查看返回的信息
