#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->setTaskByCommit();
timeout=0
cid=1

- 开始任务
 - 属性status @doing
 - 属性consumed @4
 - 属性left @3
- 工时计算
 - 属性status @doing
 - 属性consumed @11
 - 属性left @3
- 完成任务
 - 属性status @done
 - 属性consumed @14
 - 属性left @0

*/

$execution = zdTable('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,项目2,项目3,迭代1,迭代2,阶段1,阶段2,看板1,看板2');
$execution->type->range('program,project,sprint{2},stage{2},kanban{2}');
$execution->model->range('[],scrum,waterfall,kanban,[]{6}');
$execution->parent->range('0,1{3},2{2},3{2},4{2}');
$execution->project->range('0{4},2{2},3{2},4{2}');
$execution->status->range('doing');
$execution->vision->range('rnd');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

$task = zdTable('task');
$task->execution->range('3');
$task->gen(10);

$bug = zdTable('bug');
$bug->execution->range('0');
$bug->gen(10);
zdTable('repo')->config('repo')->gen(4);

global $app;
$app->rawModule = 'repo';
$app->rawMethod = 'browse';

$repoID   = 1;
$repoRoot = '';
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
$action->extra  = $scm == 'svn' ? $log->revision : substr($log->revision, 0, 10);
$action->action = 'gitcommited';

$repo = new repoTest();
$repo->setTaskByCommitTest($log, $action, $repoID);
$result = $tester->loadModel('task')->getById(1);
r($result) && p('status,consumed,left') && e('doing,4,3'); //开始任务

$log->msg = $log->comment = 'Effort Task #8 Cost:1h Left:3h';
$repo->setTaskByCommitTest($log, $action, $repoID);
$result = $tester->loadModel('task')->getById(8);
r($result) && p('status,consumed,left') && e('doing,11,3'); //工时计算

$log->msg = $log->comment = 'Finish Task #2 Cost:10h';
$repo->setTaskByCommitTest($log, $action, $repoID);
$result = $tester->loadModel('task')->getById(2);
r($result) && p('status,consumed,left') && e('done,14,0'); //完成任务