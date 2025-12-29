#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

/**

title= 测试buildTaskforEffort方法
timeout=0
cid=18862

- 传入的lastDate比record中的日期小，说明是正常记录工时，并且是完成任务的情况
 - 第0条的consumed属性 @8
 - 第0条的left属性 @0
 - 第0条的estimate属性 @0.00
 - 第0条的status属性 @done
- 传入的lastDate比record中的大，说明是补录工时，并且是完成任务的情况，但剩余不为0
 - 第0条的consumed属性 @8
 - 第0条的left属性 @1.00
 - 第0条的estimate属性 @0.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的日期小，说明是正常记录工时，并且不是完成任务的情况
 - 第0条的consumed属性 @8
 - 第0条的left属性 @0
 - 第0条的estimate属性 @0.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的大，说明是补录工时，并且不是完成任务的情况
 - 第0条的consumed属性 @8
 - 第0条的left属性 @1.00
 - 第0条的estimate属性 @0.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的日期小，说明是正常记录工时，并且是完成任务的情况
 - 第0条的consumed属性 @9
 - 第0条的left属性 @5
 - 第0条的estimate属性 @1.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的大，说明是补录工时，并且是完成任务的情况，但剩余不为0
 - 第0条的consumed属性 @9
 - 第0条的left属性 @2.00
 - 第0条的estimate属性 @1.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的日期小，说明是正常记录工时，并且不是完成任务的情况
 - 第0条的consumed属性 @9
 - 第0条的left属性 @5
 - 第0条的estimate属性 @1.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的大，说明是补录工时，并且不是完成任务的情况
 - 第0条的consumed属性 @9
 - 第0条的left属性 @2.00
 - 第0条的estimate属性 @1.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的日期小，说明是正常记录工时，并且是完成任务的情况
 - 第0条的consumed属性 @10
 - 第0条的left属性 @5
 - 第0条的estimate属性 @2.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的大，说明是补录工时，并且是完成任务的情况，但剩余不为0
 - 第0条的consumed属性 @10
 - 第0条的left属性 @3.00
 - 第0条的estimate属性 @2.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的日期小，说明是正常记录工时，并且不是完成任务的情况
 - 第0条的consumed属性 @10
 - 第0条的left属性 @5
 - 第0条的estimate属性 @2.00
 - 第0条的status属性 @doing
- 传入的lastDate比record中的大，说明是补录工时，并且不是完成任务的情况
 - 第0条的consumed属性 @10
 - 第0条的left属性 @3.00
 - 第0条的estimate属性 @2.00
 - 第0条的status属性 @doing

*/
$task = zenData('project');
$task->id->range('1-7');
$task->name->prefix("执行")->range('1-7');
$task->type->range('sprint');
$task->gen(7);

$task = zenData('task');
$task->id->range('1-7');
$task->execution->range('1-7');
$task->name->prefix("任务")->range('1-7');
$task->left->range('1-7');
$task->mode->range(" , multi, , , , ,");
$task->estStarted->range('2022\-01\-01');
$task->assignedTo->prefix("user")->range('1-7');
$task->status->range("wait,wait,doing,done,pause,cancel,closed");
$task->gen(7);

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
$effort->gen(1);

$user = zenData('user');
$user->gen(20);

$action = zenData('action');
$action->gen(0);

$finishRecord = new stdclass();
$finishRecord->consumed = 5;
$finishRecord->left     = 0;
$finishRecord->work     = "完成了任务";
$finishRecord->date     = "2022-01-01";

$startRecord = new stdclass();
$startRecord->consumed = 5;
$startRecord->left     = 5;
$startRecord->work     = "开始了任务";
$startRecord->date     = "2022-01-01";

$normalRecord = new stdclass();
$normalRecord->consumed = 5;
$normalRecord->left     = 5;
$normalRecord->work     = "记录了日志";
$normalRecord->date     = "2022-01-01";

$task = new taskTest();
r($task->buildTaskForEffortTest($finishRecord, 1, '2021-01-01', true))  && p('0:consumed,left,estimate,status') && e('8,0,0.00,done');      // 传入的lastDate比record中的日期小，说明是正常记录工时，并且是完成任务的情况
r($task->buildTaskForEffortTest($finishRecord, 1, '2023-01-01', true))  && p('0:consumed,left,estimate,status') && e('8,1.00,0.00,doing');  // 传入的lastDate比record中的大，说明是补录工时，并且是完成任务的情况，但剩余不为0
r($task->buildTaskForEffortTest($finishRecord, 1, '2021-01-01', false)) && p('0:consumed,left,estimate,status') && e('8,0,0.00,doing');     // 传入的lastDate比record中的日期小，说明是正常记录工时，并且不是完成任务的情况
r($task->buildTaskForEffortTest($finishRecord, 1, '2023-01-01', false)) && p('0:consumed,left,estimate,status') && e('8,1.00,0.00,doing');  // 传入的lastDate比record中的大，说明是补录工时，并且不是完成任务的情况
r($task->buildTaskForEffortTest($startRecord, 2, '2021-01-01', true))   && p('0:consumed,left,estimate,status') && e('9,5,1.00,doing');     // 传入的lastDate比record中的日期小，说明是正常记录工时，并且是完成任务的情况
r($task->buildTaskForEffortTest($startRecord, 2, '2023-01-01', true))   && p('0:consumed,left,estimate,status') && e('9,2.00,1.00,doing');  // 传入的lastDate比record中的大，说明是补录工时，并且是完成任务的情况，但剩余不为0
r($task->buildTaskForEffortTest($startRecord, 2, '2021-01-01', false))  && p('0:consumed,left,estimate,status') && e('9,5,1.00,doing');     // 传入的lastDate比record中的日期小，说明是正常记录工时，并且不是完成任务的情况
r($task->buildTaskForEffortTest($startRecord, 2, '2023-01-01', false))  && p('0:consumed,left,estimate,status') && e('9,2.00,1.00,doing');  // 传入的lastDate比record中的大，说明是补录工时，并且不是完成任务的情况
r($task->buildTaskForEffortTest($normalRecord, 3, '2021-01-01', true))  && p('0:consumed,left,estimate,status') && e('10,5,2.00,doing');    // 传入的lastDate比record中的日期小，说明是正常记录工时，并且是完成任务的情况
r($task->buildTaskForEffortTest($normalRecord, 3, '2023-01-01', true))  && p('0:consumed,left,estimate,status') && e('10,3.00,2.00,doing'); // 传入的lastDate比record中的大，说明是补录工时，并且是完成任务的情况，但剩余不为0
r($task->buildTaskForEffortTest($normalRecord, 3, '2021-01-01', false)) && p('0:consumed,left,estimate,status') && e('10,5,2.00,doing');    // 传入的lastDate比record中的日期小，说明是正常记录工时，并且不是完成任务的情况
r($task->buildTaskForEffortTest($normalRecord, 3, '2023-01-01', false)) && p('0:consumed,left,estimate,status') && e('10,3.00,2.00,doing'); // 传入的lastDate比record中的大，说明是补录工时，并且不是完成任务的情况
