#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=测试记录任务的工时
timeout=0
cid=1

*/

$execution = zdTable('project');
$execution->gen(7);

$task = zdTable('task');
$task->id->range('1-7');
$task->execution->range('1-7');
$task->name->prefix("任务")->range('1-7');
$task->left->range('1-7');
$task->mode->range(" , multi, , , , ,");
$task->estStarted->range('2022\-01\-01');
$task->assignedTo->prefix("user")->range('1-7');
$task->status->range("wait,wait,doing,done,pause,cancel,closed");
$task->gen(7);

$taskteam = zdTable('taskteam');
$taskteam->id->range('1-5');
$taskteam->task->range('2');
$taskteam->account->prefix("user")->range('1-5');
$taskteam->estimate->range('5');
$taskteam->consumed->range('0');
$taskteam->left->range('5');
$taskteam->status->range("wait");
$taskteam->gen(5);

$effort = zdTable('effort');
$effort->gen(1);

$user = zdTable('user');
$user->gen(20);

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
r($task->recordWorkhourTest(5, $noconsumedTaskEffort))    && p('consumed[1]')     && e('请填写"耗时"');                    // 无消耗时返回提示信息，因为没有填写消耗所以应该提示填写耗时
