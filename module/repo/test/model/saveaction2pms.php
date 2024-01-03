#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->saveAction2PMS();
timeout=0
cid=1

- 开始任务
 - 第1条的status属性 @doing
 - 第1条的consumed属性 @4
 - 第1条的left属性 @3
- 完成任务
 - 第2条的status属性 @done
 - 第2条的consumed属性 @14
 - 第2条的left属性 @0
- 工时计算
 - 第8条的status属性 @doing
 - 第8条的consumed属性 @11
 - 第8条的left属性 @3
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
$log->comment   = 'Start Task #1 Cost:1h Left:3h,Effort Task #8 Cost:1h Left:3h,Finish Task #2 Cost:10h';
$log->author    = 'user4';
$log->msg       = 'Start Task #1 Cost:1h Left:3h,Effort Task #8 Cost:1h Left:3h,Finish Task #2 Cost:10h';
$log->date      = '2023-12-29 10:44:36';
$log->files     = array('M' => array('/README.md'));
$log->change    = array('/README.md' => array('action' => 'M', 'kind' => 'file', 'oldPath' => ''));

$repo = new repoTest();
$repo->saveAction2PMSTest($log, $repoID);
$result = $tester->loadModel('task')->getByIdList(array(1,2,8));
r($result) && p('1:status,consumed,left') && e('doing,4,3'); //开始任务
r($result) && p('2:status,consumed,left') && e('done,14,0'); //完成任务
r($result) && p('8:status,consumed,left') && e('doing,11,3'); //工时计算

$log->msg = $log->comment = 'Fix bug#1,2';
$repo->saveAction2PMSTest($log, $repoID);
$result = $tester->loadModel('bug')->getByIdList(array(1,2));
r($result) && p('1:status,resolution') && e('resolved,fixed'); //修复bug1
r($result) && p('2:status,resolution') && e('resolved,fixed'); //修复bug2
