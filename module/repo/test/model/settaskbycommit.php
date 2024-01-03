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

zdTable('task')->gen(10);
$bug = zdTable('bug');
$bug->execution->range('0');
$bug->gen(10);
zdTable('repo')->config('repo')->gen(4);

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
