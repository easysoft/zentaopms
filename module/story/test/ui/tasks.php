#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/tasks.ui.class.php';

/**

title=开源版m=story&f=tasks测试
timeout=0
cid=1

- 开源版m=story&f=tasks测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=story&f=tasks测试通过

*/

$projectMain = zenData('project');
$projectMain->id->range('1');
$projectMain->name->range('项目1');
$projectMain->type->range('project');
$projectMain->multiple->range('1');
$projectMain->vision->range('rnd');
$projectMain->isTpl->range('0');
$projectMain->status->range('doing');
$projectMain->gen(1);

$execution = zenData('project');
$execution->id->range('101');
$execution->name->range('迭代1');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->multiple->range('1');
$execution->vision->range('rnd');
$execution->isTpl->range('0');
$execution->status->range('doing');
$execution->gen(1, false);

$team = zenData('team');
$team->id->range('1');
$team->root->range('101');
$team->type->range('execution');
$team->account->range('admin');
$team->role->range('dev');
$team->join->range('`2025-10-01`');
$team->days->range('10');
$team->hours->range('8');
$team->gen(1);

$story = zenData('story');
$story->id->range('1');
$story->product->range('1');
$story->title->range('需求1');
$story->status->range('active');
$story->version->range('1');
$story->vision->range('rnd');
$story->openedBy->range('admin');
$story->openedDate->range('`2025-11-01 09:00:00`');
$story->assignedTo->range('admin');
$story->stage->range('wait');
$story->gen(1);

$task = zenData('task');
$task->id->range('1-8');
$task->name->range('1-8')->prefix('任务');
$task->type->range('devel');
$task->pri->range('1,2,3,4');
$task->estimate->range('1,2,4,8');
$task->consumed->range('0,1,2,4');
$task->left->range('0,1,2,4');
$task->status->range('wait{2},doing{2},done{2},closed{2}');
$task->project->range('1');
$task->execution->range('101');
$task->story->range('1');
$task->storyVersion->range('1');
$task->assignedTo->range('admin');
$task->assignedDate->range('`2025-11-02 10:00:00`');
$task->openedBy->range('admin');
$task->openedDate->range('`2025-11-02 10:00:00`');
$task->lastEditedBy->range('admin');
$task->lastEditedDate->range('`2025-11-03 09:00:00`');
$task->vision->range('rnd');
$task->deadline->range('`2025-11-30`');
$task->gen(8);

$tester = new tasksTester();
r($tester->verifyTasksView(1, 101)) && p('status,message') && e('SUCCESS,开源版m=story&f=tasks测试通过'); // 开源版m=story&f=tasks测试

$tester->closeBrowser();
