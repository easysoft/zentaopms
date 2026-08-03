#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->setTaskByCommit();
timeout=0
cid=18105

- 执行repo模块的setTaskByCommitTaskTest方法，参数是$log, $action, $repoID, 1
 - 属性status @doing
 - 属性consumed @4.00
 - 属性left @3.00
- 执行repo模块的setTaskByCommitTaskTest方法，参数是$log, $action, $repoID, 8
 - 属性status @doing
 - 属性consumed @11.00
 - 属性left @3.00
- 执行repo模块的setTaskByCommitTaskTest方法，参数是$log, $action, $repoID, 2
 - 属性status @done
 - 属性consumed @14.00
 - 属性left @0.00
- 执行repo模块的setTaskByCommitTest方法，参数是$log, $action, $repoID) === false  @1
- 执行repo模块的setTaskByCommitTest方法，参数是$log, $action, $repoID) === false  @1

*/

zenData('project')->gen(0);
$projectData = zenData('project');
$projectData->id->range('3');
$projectData->project->range('3');
$projectData->name->range('repo-test-execution');
$projectData->code->range('repo-test-execution');
$projectData->type->range('sprint');
$projectData->status->range('wait');
$projectData->grade->range('1');
$projectData->parent->range('0');
$projectData->path->range(',3,');
$projectData->acl->range('open');
$projectData->multiple->range('1');
$projectData->vision->range('rnd');
$projectData->deleted->range('0');
$projectData->gen(1);

zenData('task')->gen(0);
zenData('effort')->gen(0);
zenData('action')->gen(0);
zenData('history')->gen(0);

$taskData = zenData('task');
$taskData->id->range('1,2,8');
$taskData->project->range('3');
$taskData->execution->range('3');
$taskData->module->range('21,24,42');
$taskData->story->range('0');
$taskData->name->range('开发任务11,开发任务12,开发任务18');
$taskData->type->range('design,devel,misc');
$taskData->consumed->range('3,4,10');
$taskData->left->range('0,1,4');
$taskData->status->range('wait,doing,doing');
$taskData->openedBy->range('admin');
$taskData->deleted->range('0');
$taskData->mode->range('none');
$taskData->gen(3);

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

r($repo->setTaskByCommitTaskTest($log, $action, $repoID, 1)) && p('status,consumed,left') && e('doing,4.00,3.00');

$log->msg = $log->comment = 'Effort Task #8 Cost:1h Left:3h';
r($repo->setTaskByCommitTaskTest($log, $action, $repoID, 8)) && p('status,consumed,left') && e('doing,11.00,3.00');

$log->msg = $log->comment = 'Finish Task #2 Cost:10h';
r($repo->setTaskByCommitTaskTest($log, $action, $repoID, 2)) && p('status,consumed,left') && e('done,14.00,0.00');

$log->msg = $log->comment = 'No Task match in this message';
r($repo->setTaskByCommitTest($log, $action, $repoID) === false) && p() && e('1');

$log->msg = $log->comment = 'Start Task #999 Cost:1h Left:1h';
r($repo->setTaskByCommitTest($log, $action, $repoID) === false) && p() && e('1');
