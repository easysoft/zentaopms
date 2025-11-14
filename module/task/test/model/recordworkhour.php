#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

/**

title=测试taskModel->recordWorkHour();
timeout=0
cid=18841

- 任务未开始时记录工时，查看已消耗工时，应该在之前消耗的基础上增加测试设置的消耗值
 - 属性field @consumed
 - 属性old @3
 - 属性new @8
- 任务未开始时记录工时，查看状态是否变化，应该从未开始变为开始
 - 属性field @status
 - 属性old @wait
 - 属性new @doing
- 任务未开始时记录工时，查看指派给是否变化，应该从之前用户变为当前用户
 - 属性field @assignedTo
 - 属性old @user1
 - 属性new @admin
- 不在多人任务团队中的用户记录工时，直接返回false @0
- 在多人任务团队中的用户记录工时，查看返回的最初预计工时
 - 属性field @estimate
 - 属性old @1
 - 属性new @25
- 在多人任务团队中的用户记录工时，查看返回的消耗工时
 - 属性field @consumed
 - 属性old @4
 - 属性new @2
- 在多人任务团队中的用户记录工时，查看返回的剩余工时
 - 属性field @left
 - 属性old @2
 - 属性new @21
- 通过记录日志直接完成任务的情况
 - 第0条的field属性 @consumed
 - 第0条的old属性 @5
 - 第0条的new属性 @10
- 正常记录工时
 - 第2条的field属性 @status
 - 第2条的old属性 @done
 - 第2条的new属性 @doing
- 正常记录工时
 - 第2条的field属性 @status
 - 第2条的old属性 @cancel
 - 第2条的new属性 @doing
- 正常记录工时
 - 第2条的field属性 @status
 - 第2条的old属性 @closed
 - 第2条的new属性 @doing
- 无消耗时返回提示信息，因为没有填写消耗所以应该提示填写耗时属性consumed[1] @请填写"耗时"

*/

$execution = zenData('project');
$execution->gen(7);

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

zenData('effort')->gen(1);
zenData('user')->gen(20);
zenData('taskteam')->gen(10);

$multiTaskEffort = array();
for($i = 1; $i <= 5; $i++)
{
    $multiTaskEffort[$i] = new stdclass();
    $multiTaskEffort[$i]->consumed = 2;
    $multiTaskEffort[$i]->left     = 1;
    $multiTaskEffort[$i]->work     = "工作内容$i";
    $multiTaskEffort[$i]->date     = "2022-01-01";
}

$finishTaskEffort = array();
$finishTaskEffort[1] = new stdclass();
$finishTaskEffort[1]->consumed = 5;
$finishTaskEffort[1]->left     = 0;
$finishTaskEffort[1]->work     = "完成了任务";
$finishTaskEffort[1]->date     = "2022-01-01";

$startTaskEffort = array();
$startTaskEffort[1] = new stdclass();
$startTaskEffort[1]->consumed = 5;
$startTaskEffort[1]->left     = 5;
$startTaskEffort[1]->work     = "开始了任务";
$startTaskEffort[1]->date     = "2022-01-01";

$normalTaskEffort = array();
$normalTaskEffort[1] = new stdclass();
$normalTaskEffort[1]->consumed = 5;
$normalTaskEffort[1]->left     = 5;
$normalTaskEffort[1]->work     = "记录了日志";
$normalTaskEffort[1]->date     = "2022-01-01";

$noconsumedTaskEffort = array();
$noconsumedTaskEffort[1] = new stdclass();
$noconsumedTaskEffort[1]->consumed = 0; // 相当于未填写消耗
$noconsumedTaskEffort[1]->left     = 2;
$noconsumedTaskEffort[1]->work     = "无消耗的日志";
$noconsumedTaskEffort[1]->date     = "2022-01-01";

$task = new taskTest();
$startTaskResult = $task->recordWorkhourTest(1, $startTaskEffort);
r($startTaskResult[0]) && p('field,old,new') && e('consumed,3,8');           // 任务未开始时记录工时，查看已消耗工时，应该在之前消耗的基础上增加测试设置的消耗值
r($startTaskResult[1]) && p('field,old,new') && e('status,wait,doing');      // 任务未开始时记录工时，查看状态是否变化，应该从未开始变为开始
r($startTaskResult[2]) && p('field,old,new') && e('assignedTo,user1,admin'); // 任务未开始时记录工时，查看指派给是否变化，应该从之前用户变为当前用户

su('admin');
$multiTaskResult = $task->recordWorkhourTest(2, $multiTaskEffort);
r($multiTaskResult) && p() && e('0');  // 不在多人任务团队中的用户记录工时，直接返回false

su('user2');
$multiTaskResult = $task->recordWorkhourTest(2, $multiTaskEffort);
r($multiTaskResult[0]) && p('field,old,new') && e('estimate,1,25');  // 在多人任务团队中的用户记录工时，查看返回的最初预计工时
r($multiTaskResult[1]) && p('field,old,new') && e('consumed,4,2');   // 在多人任务团队中的用户记录工时，查看返回的消耗工时
r($multiTaskResult[2]) && p('field,old,new') && e('left,2,21');      // 在多人任务团队中的用户记录工时，查看返回的剩余工时

r($task->recordWorkhourTest(3, $finishTaskEffort))        && p('0:field,old,new') && e('consumed,5,10');                 // 通过记录日志直接完成任务的情况
r($task->recordWorkhourTest(4, $normalTaskEffort))        && p('2:field,old,new') && e('status,done,doing');             // 正常记录工时
r($task->recordWorkhourTest(6, $normalTaskEffort))        && p('2:field,old,new') && e('status,cancel,doing');           // 正常记录工时
r($task->recordWorkhourTest(7, $normalTaskEffort))        && p('2:field,old,new') && e('status,closed,doing');           // 正常记录工时
r($task->recordWorkhourTest(5, $noconsumedTaskEffort))    && p('consumed[1]')     && e('请填写"耗时"');                  // 无消耗时返回提示信息，因为没有填写消耗所以应该提示填写耗时
