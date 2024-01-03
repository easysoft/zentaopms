#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->saveRecord();
timeout=0
cid=1

- 保存一条记录
 - 属性action @gitcommited
 - 属性extra @61e51cadb1
- 更新已有记录属性actor @admin
- 查看历史记录属性field @git

*/

zdTable('task')->gen(10);
zdTable('repo')->config('repo')->gen(4);

$taskID   = 1;
$repoRoot = '';
$scm      = 'gitlab';

$log = new stdclass();
$log->revision  = '61e51cadb1aa21ef3d2b51e3f193be3cc19cfef6';
$log->committer = 'root';
$log->time      = '2023-12-29 10:44:36';
$log->comment   = 'Start Task #64250 Cost:1h Left:3h  Finish Task #64247 Cost:10h';
$log->author    = '';
$log->msg       = 'Start Task #64250 Cost:1h Left:3h  Finish Task #64247 Cost:10h';
$log->date      = '2023-12-29 10:44:36';
$log->files     = array('M' => array('/README.md'));
$log->change    = array('/README.md' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$action  = new stdclass();
$action->actor      = 'user4';
$action->date       = '2023-12-29 13:14:36';
$action->extra      = $scm == 'svn' ? $log->revision : substr($log->revision, 0, 10);
$action->objectType = 'task';
$action->objectID   = $taskID;
$action->action     = 'gitcommited';

$repo = new repoTest();

r($repo->saveRecordTest($action, $log, $repoRoot, $scm)) && p('action,extra') && e('gitcommited,61e51cadb1'); //保存一条记录

$action->actor = 'admin';
r($repo->saveRecordTest($action, $log, $repoRoot, $scm)) && p('actor') && e('admin'); //更新已有记录

r($repo->saveRecordTest($action, $log, $repoRoot, $scm, true)) && p('field') && e('git'); //查看历史记录
