#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('user1');

/**

title=检查根据effortID更新任务团队工时
timeout=0
cid=18893

- 通过ID为1的日志更新任务团队工时第0条的estimate属性 @5.00
- 通过ID为2的日志更新任务团队工时第0条的consumed属性 @10.00
- 通过ID为3的日志更新任务团队工时第0条的consumed属性 @0.00
- 通过ID为3的日志更新任务团队工时第0条的consumed属性 @1.00
- 通过ID为4的日志更新任务团队工时第0条的consumed属性 @0.00
- 通过ID为4的日志更新任务团队工时第0条的consumed属性 @1.00

*/
$task = zenData('task');
$task->id->range('1-7');
$task->execution->range('1-7');
$task->name->prefix("任务")->range('1-7');
$task->left->range('1-7');
$task->mode->range("linear,multi");
$task->estStarted->range('2022\-01\-01');
$task->assignedTo->prefix("user")->range('1-7');
$task->status->range("wait,wait,doing,done,pause,cancel,closed");
$task->gen(4);

$taskteam = zenData('taskteam');
$taskteam->id->range('1-20');
$taskteam->task->range('1-4');
$taskteam->account->prefix("user")->range('1-5');
$taskteam->estimate->range('5');
$taskteam->consumed->range('0,1');
$taskteam->left->range('5');
$taskteam->status->range("wait");
$taskteam->gen(20);

$effort = zenData('effort');
$effort->id->range('1-20');
$effort->objectID->range('1-4');
$effort->objectType->range('task');
$effort->account->prefix("user")->range('1-5');
$effort->consumed->range('0,1');
$effort->left->range('5');
$effort->date->range('2022\-01\-01');
$effort->work->prefix('工作内容')->range('1-10');
$effort->gen(10);

$user = zenData('user');
$user->gen(20);

$finishTaskEffort = new stdclass();
$finishTaskEffort->consumed = 5;
$finishTaskEffort->left     = 0;
$finishTaskEffort->work     = "完成了任务";
$finishTaskEffort->date     = "2022-01-01";

$startTaskEffort = new stdclass();
$startTaskEffort->consumed = 5;
$startTaskEffort->left     = 5;
$startTaskEffort->work     = "开始了任务";
$startTaskEffort->date     = "2022-01-01";

$normalTaskEffort = new stdclass();
$normalTaskEffort->consumed = 5;
$normalTaskEffort->left     = 5;
$normalTaskEffort->work     = "记录了日志";
$normalTaskEffort->date     = "2022-01-01";

$lastDate = '2022-01-01';

$task = new taskTest();
r($task->updateTeamByEffortTest(1, $finishTaskEffort, 1, null, $lastDate)) && p('0:estimate') && e('5.00');   // 通过ID为1的日志更新任务团队工时
r($task->updateTeamByEffortTest(2, $finishTaskEffort, 1, null, $lastDate)) && p('0:consumed') && e('10.00');  // 通过ID为2的日志更新任务团队工时
r($task->updateTeamByEffortTest(3, $startTaskEffort,  3, null, $lastDate)) && p('0:consumed') && e('0.00');   // 通过ID为3的日志更新任务团队工时
r($task->updateTeamByEffortTest(3, $startTaskEffort,  4, null, $lastDate)) && p('0:consumed') && e('1.00');   // 通过ID为3的日志更新任务团队工时
r($task->updateTeamByEffortTest(4, $normalTaskEffort, 3, null, $lastDate)) && p('0:consumed') && e('0.00');   // 通过ID为4的日志更新任务团队工时
r($task->updateTeamByEffortTest(4, $normalTaskEffort, 4, null, $lastDate)) && p('0:consumed') && e('1.00');   // 通过ID为4的日志更新任务团队工时