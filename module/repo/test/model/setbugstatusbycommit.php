#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->setBugStatusByCommit();
timeout=0
cid=1

- 修复bug1
 - 第1条的status属性 @resolved
 - 第1条的resolution属性 @fixed
- 修复bug2
 - 第2条的status属性 @resolved
 - 第2条的resolution属性 @fixed

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
$log->comment   = 'Fix bug#1,2';
$log->author    = 'user4';
$log->msg       = 'Fix bug#1,2';
$log->date      = '2023-12-29 10:44:36';
$log->files     = array('M' => array('/README.md'));
$log->change    = array('/README.md' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$action  = new stdclass();
$action->actor  = 'user4';
$action->date   = '2023-12-29 13:14:36';
$action->extra  = $scm == 'svn' ? $log->revision : substr($log->revision, 0, 10);
$action->action = 'gitcommited';

$repo = new repoTest();
$repo->setBugStatusByCommitTest($log, $action, $repoID);
$result = $tester->loadModel('bug')->getByIdList(array(1,2));
r($result) && p('1:status,resolution') && e('resolved,fixed'); //修复bug1
r($result) && p('2:status,resolution') && e('resolved,fixed'); //修复bug2
