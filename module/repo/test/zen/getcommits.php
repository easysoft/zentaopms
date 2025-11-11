#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getCommits();
timeout=0
cid=0

- 测试步骤1:获取Git版本库的提交记录 @3
- 测试步骤2:获取Subversion版本库的提交记录 @3
- 测试步骤3:获取指定文件的提交记录 @1
- 测试步骤4:传入无效的repo对象 @0
- 测试步骤5:传入无效的type参数 @0
- 测试步骤6:使用不同分页参数获取提交记录 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

ob_start();
zenData('repo')->loadYaml('repo', false, 2)->gen(10);
zenData('repohistory')->loadYaml('repohistory', false, 2)->gen(50);
zenData('user')->gen(5);
ob_end_clean();

su('admin');

$repoTest = new repoZenTest();

$pager1 = new stdClass();
$pager1->recPerPage = 20;
$pager1->pageID = 1;

$pager2 = new stdClass();
$pager2->recPerPage = 10;
$pager2->pageID = 1;

global $tester;
$repoModel = $tester->loadModel('repo');

$repo1 = new stdClass();
$repo1->id = 1;
$repo1->SCM = 'Git';
$repo1->name = 'test-repo';

$repo2 = new stdClass();
$repo2->id = 2;
$repo2->SCM = 'Subversion';
$repo2->name = 'svn-repo';

$invalidRepo = new stdClass();
$invalidRepo->id = 0;

r(count($repoTest->getCommitsTest($repo1, '', 'HEAD', 'dir', $pager1, 1))) && p() && e('3'); // 测试步骤1:获取Git版本库的提交记录
r(count($repoTest->getCommitsTest($repo2, '', 'HEAD', 'dir', $pager1, 1))) && p() && e('3'); // 测试步骤2:获取Subversion版本库的提交记录
r(count($repoTest->getCommitsTest($repo1, '/src/index.php', 'HEAD', 'file', $pager1, 1))) && p() && e('1'); // 测试步骤3:获取指定文件的提交记录
r(count($repoTest->getCommitsTest($invalidRepo, '', 'HEAD', 'dir', $pager1, 1))) && p() && e('0'); // 测试步骤4:传入无效的repo对象
r(count($repoTest->getCommitsTest($repo1, '', 'HEAD', 'invalid', $pager1, 1))) && p() && e('0'); // 测试步骤5:传入无效的type参数
r(count($repoTest->getCommitsTest($repo1, '', 'HEAD', 'dir', $pager2, 1))) && p() && e('3'); // 测试步骤6:使用不同分页参数获取提交记录