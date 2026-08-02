#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->saveAction2PMS();
timeout=0
cid=18093

- 开始任务
 - 第1条的status属性 @wait
 - 第1条的consumed属性 @3.00
 - 第1条的left属性 @0.00
- 完成任务
 - 第2条的status属性 @doing
 - 第2条的consumed属性 @4.00
 - 第2条的left属性 @1.00
- 工时计算
 - 第8条的status属性 @doing
 - 第8条的consumed属性 @11.00
 - 第8条的left属性 @3.00
- 修复bug1
 - 第1条的status属性 @resolved
 - 第1条的resolution属性 @fixed
- 修复bug2
 - 第2条的status属性 @resolved
 - 第2条的resolution属性 @fixed

*/
global $tester;
$tester->dao->delete()->from(TABLE_PROJECT)->where('id')->in('1,2,3')->exec();
$tester->dao->delete()->from(TABLE_TASK)->where('id')->in('1,2,8')->exec();
$tester->dao->delete()->from(TABLE_BUG)->where('id')->in('1,2')->exec();
$tester->dao->delete()->from(TABLE_EFFORT)->where('objectType')->eq('task')->andWhere('objectID')->in('1,2,8')->exec();
$tester->dao->delete()->from(TABLE_ACTION)->where('objectType')->in('task,bug')->andWhere('objectID')->in('1,2,8')->exec();
$tester->dao->delete()->from(TABLE_HISTORY)->where('field')->eq('git')->exec();
$tester->dao->delete()->from(TABLE_ACTION)->where('action')->eq('gitcommited')->exec();

$executions = array(
    array('id' => 1, 'name' => '项目集1', 'type' => 'program', 'status' => 'doing', 'model' => '',      'parent' => 0, 'project' => 0, 'path' => ',1,',     'grade' => 1, 'code' => 'program1', 'begin' => '2026-07-09', 'end' => '2026-07-16', 'acl' => 'open', 'openedBy' => 'admin', 'openedDate' => '2026-07-09 00:00:00', 'vision' => 'rnd'),
    array('id' => 2, 'name' => '项目1',   'type' => 'project', 'status' => 'doing', 'model' => 'scrum', 'parent' => 1, 'project' => 0, 'path' => ',1,2,',   'grade' => 2, 'code' => 'project1', 'begin' => '2026-07-09', 'end' => '2026-07-16', 'acl' => 'open', 'openedBy' => 'admin', 'openedDate' => '2026-07-09 00:00:00', 'vision' => 'rnd'),
    array('id' => 3, 'name' => '迭代1',   'type' => 'sprint',  'status' => 'doing', 'model' => '',      'parent' => 2, 'project' => 2, 'path' => ',1,2,3,', 'grade' => 3, 'code' => 'sprint1',  'begin' => '2026-07-09', 'end' => '2026-07-16', 'acl' => 'open', 'openedBy' => 'admin', 'openedDate' => '2026-07-09 00:00:00', 'vision' => 'rnd')
);
foreach($executions as $execution) $tester->dao->insert(TABLE_PROJECT)->data($execution)->exec();

$tasks = array(
    array('id' => 1, 'parent' => 0, 'project' => 11, 'execution' => 3, 'module' => 21, 'story' => 1,  'design' => 0, 'storyVersion' => 1, 'designVersion' => 1, 'fromBug' => 0, 'name' => '开发任务11', 'type' => 'design', 'pri' => 1, 'estimate' => 0, 'consumed' => 3,  'left' => 0, 'deadline' => '2026-07-16', 'status' => 'wait',  'subStatus' => '', 'color' => '', 'mailto' => '', 'desc' => '这里是任务描述1', 'version' => 1, 'openedBy' => 'admin', 'openedDate' => '2026-07-09 00:00:00', 'assignedTo' => '', 'assignedDate' => '2026-07-09 00:00:00', 'estStarted' => '2026-07-09', 'realStarted' => '2026-07-09 00:00:00', 'finishedBy' => '', 'finishedList' => '', 'canceledBy' => '', 'closedBy' => '', 'realDuration' => 1, 'planDuration' => 1, 'closedReason' => '', 'lastEditedBy' => '', 'deleted' => 0, 'mode' => 'linear'),
    array('id' => 2, 'parent' => 0, 'project' => 12, 'execution' => 3, 'module' => 24, 'story' => 5,  'design' => 0, 'storyVersion' => 1, 'designVersion' => 1, 'fromBug' => 0, 'name' => '开发任务12', 'type' => 'devel',  'pri' => 2, 'estimate' => 1, 'consumed' => 4,  'left' => 1, 'deadline' => '2026-07-15', 'status' => 'doing', 'subStatus' => '', 'color' => '', 'mailto' => '', 'desc' => '这里是任务描述2', 'version' => 1, 'openedBy' => 'admin', 'openedDate' => '2026-07-09 00:00:00', 'assignedTo' => '', 'assignedDate' => '2026-07-09 00:00:00', 'estStarted' => '2026-07-09', 'realStarted' => '2026-07-09 00:00:00', 'finishedBy' => '', 'finishedList' => '', 'canceledBy' => '', 'closedBy' => '', 'realDuration' => 1, 'planDuration' => 1, 'closedReason' => '', 'lastEditedBy' => '', 'deleted' => 0, 'mode' => 'linear'),
    array('id' => 8, 'parent' => 0, 'project' => 18, 'execution' => 3, 'module' => 42, 'story' => 29, 'design' => 0, 'storyVersion' => 1, 'designVersion' => 1, 'fromBug' => 0, 'name' => '开发任务18', 'type' => 'misc',   'pri' => 4, 'estimate' => 7, 'consumed' => 10, 'left' => 4, 'deadline' => '2026-07-09', 'status' => 'doing', 'subStatus' => '', 'color' => '', 'mailto' => '', 'desc' => '这里是任务描述8', 'version' => 1, 'openedBy' => 'admin', 'openedDate' => '2026-07-09 00:00:00', 'assignedTo' => '', 'assignedDate' => '2026-07-09 00:00:00', 'estStarted' => '2026-07-09', 'realStarted' => '2026-07-09 00:00:00', 'finishedBy' => '', 'finishedList' => '', 'canceledBy' => '', 'closedBy' => '', 'realDuration' => 1, 'planDuration' => 1, 'closedReason' => '', 'lastEditedBy' => '', 'deleted' => 0, 'mode' => 'linear')
);
foreach($tasks as $task) $tester->dao->insert(TABLE_TASK)->data($task)->exec();

$bugs = array(
    array('id' => 1, 'project' => 11, 'product' => 1, 'module' => 1821, 'execution' => 0, 'plan' => 1, 'story' => 2, 'storyVersion' => 1, 'title' => 'BUG1', 'severity' => 1, 'pri' => 1, 'type' => 'codeerror', 'steps' => 'step1', 'status' => 'active', 'color' => '#3da7f5', 'openedBy' => 'admin', 'openedDate' => '2026-07-09 00:00:00', 'openedBuild' => '1', 'assignedTo' => 'admin', 'assignedDate' => '2026-07-09 00:00:00', 'deadline' => '2026-07-16', 'resolution' => '', 'deleted' => 0),
    array('id' => 2, 'project' => 11, 'product' => 1, 'module' => 1822, 'execution' => 0, 'plan' => 1, 'story' => 6, 'storyVersion' => 1, 'title' => 'BUG2', 'severity' => 2, 'pri' => 2, 'type' => 'config',    'steps' => 'step2', 'status' => 'active', 'color' => '#75c941', 'openedBy' => 'admin', 'openedDate' => '2026-07-09 00:00:00', 'openedBuild' => '1', 'assignedTo' => 'admin', 'assignedDate' => '2026-07-09 00:00:00', 'deadline' => '2026-07-15', 'resolution' => '', 'deleted' => 0)
);
foreach($bugs as $bug) $tester->dao->insert(TABLE_BUG)->data($bug)->exec();

global $app;
$app->rawModule = 'repo';
$app->rawMethod = 'browse';

include($app->getModuleRoot() . '/repo/control.php');
$app->control = new repo();

$repoID   = 1;
$repoRoot = '';
$scm      = 'gitlab';

$log = new stdclass();
$log->revision  = '61e51cadb1aa21ef3d2b51e3f193be3cc19cfef6';
$log->committer = 'root';
$log->time      = '2023-12-29 10:44:36';
$log->comment   = 'Start Task #1 Cost:1h Left:3h,Effort Task #8 Cost:1h Left:3h,Finish Task #2 Cost:10h';
$log->author    = 'user4';
$log->msg       = 'Start Task #1 Cost:1h Left:3h,Effort Task #8 Cost:1h Left:3h,Finish Task #2 Cost:10h';
$log->date      = '2023-12-29 10:44:36';
$log->files     = array('M' => array('/README.md'));
$log->change    = array('/README.md' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$repo = new repoModelTest();
r($repo->saveAction2PMSTaskListTest($log, $repoID, array(1, 2, 8))) && p('1:status,consumed,left') && e('wait,3.00,0.00'); //开始任务
r($repo->saveAction2PMSTaskListTest($log, $repoID, array(1, 2, 8))) && p('2:status,consumed,left') && e('doing,4.00,1.00'); //完成任务
r($repo->saveAction2PMSTaskListTest($log, $repoID, array(1, 2, 8))) && p('8:status,consumed,left') && e('doing,11.00,3.00');  //工时计算
$log->msg = $log->comment = 'Fix bug#1,2';
r($repo->saveAction2PMSBugListTest($log, $repoID, array(1, 2))) && p('1:status,resolution') && e('resolved,fixed'); //修复bug1
r($repo->saveAction2PMSBugListTest($log, $repoID, array(1, 2))) && p('2:status,resolution') && e('resolved,fixed'); //修复bug2