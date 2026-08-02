#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->setTaskByCommit();
timeout=0
cid=18105

- 开始任务
 - 属性status @changed
 - 属性consumed @3.00
 - 属性left @0.00
- 工时计算
 - 属性status @doing
 - 属性consumed @11.00
 - 属性left @3.00
- 完成任务
 - 属性status @changed
 - 属性consumed @4.00
 - 属性left @1.00
- 无效消息返回false >> 1
- 不存在任务返回false >> 1

*/

global $tester;
$tester->dao->delete()->from(TABLE_PROJECT)->where('id')->in('1,2,3')->exec();
$tester->dao->delete()->from(TABLE_TASK)->where('id')->in('1,2,8')->exec();
$tester->dao->delete()->from(TABLE_EFFORT)->where('objectType')->eq('task')->andWhere('objectID')->in('1,2,8')->exec();
$tester->dao->delete()->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('objectID')->in('1,2,8')->exec();
$tester->dao->delete()->from(TABLE_HISTORY)->where('field')->eq('git')->exec();

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

global $app;
$app->rawModule = 'repo';
$app->rawMethod = 'browse';

$repoID   = 1;
$scm      = 'gitlab';

$log = new stdclass();
$log->revision  = '61e51cadb1aa21ef3d2b51e3f193be3cc19cfef6';
$log->committer = 'root';
$log->time      = '2023-12-29 10:44:36';
$log->comment   = 'Start Task #1 Cost:1h Left:3h';
$log->author    = 'user4';
$log->msg       = 'Start Task #1 Cost:1h Left:3h';
$log->date      = '2023-12-29 10:44:36';
$log->files     = array('M' => array('/README.md'));
$log->change    = array('/README.md' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$action  = new stdclass();
$action->actor  = 'user4';
$action->date   = '2023-12-29 13:14:36';
$action->extra  = substr($log->revision, 0, 10);
$action->action = 'gitcommited';

include($app->getModuleRoot() . '/repo/control.php');
$app->control = new repo();

$repo = new repoModelTest();

$repo->setTaskByCommitTest($log, $action, $repoID);
r($repo->setTaskByCommitTaskTest($log, $action, $repoID, 1)) && p('status,consumed,left') && e('changed,3.00,0.00');

$log->msg = $log->comment = 'Effort Task #8 Cost:1h Left:3h';
$repo->setTaskByCommitTest($log, $action, $repoID);
r($repo->setTaskByCommitTaskTest($log, $action, $repoID, 8)) && p('status,consumed,left') && e('doing,11.00,3.00');

$log->msg = $log->comment = 'Finish Task #2 Cost:10h';
$repo->setTaskByCommitTest($log, $action, $repoID);
r($repo->setTaskByCommitTaskTest($log, $action, $repoID, 2)) && p('status,consumed,left') && e('changed,4.00,1.00');

$log->msg = $log->comment = 'No Task match in this message';
r($repo->setTaskByCommitTest($log, $action, $repoID) === false) && p() && e('1');

$log->msg = $log->comment = 'Start Task #999 Cost:1h Left:1h';
r($repo->setTaskByCommitTest($log, $action, $repoID) === false) && p() && e('1');
