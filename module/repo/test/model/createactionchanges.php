#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->createActionChanges();
timeout=0
cid=1

- 设置svn的日志第0条的field属性 @subversion
- 设置gitlab的日志第0条的field属性 @git
- 设置错误的日志类型第0条的field属性 @git
- 没有改动文件 @0

*/

$repo = new repoTest();

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

r($repo->createActionChangesTest($log, 'url/', 'svn'))    && p('0:field') && e('subversion'); // 设置svn的日志
r($repo->createActionChangesTest($log, 'url/', 'gitlab')) && p('0:field') && e('git');        // 设置gitlab的日志
r($repo->createActionChangesTest($log, 'url/', 'errgit')) && p('0:field') && e('git');        // 设置错误的日志类型

unset($log->files);
r($repo->createActionChangesTest($log, 'url/', 'git')) && p() && e('0'); // 没有改动文件