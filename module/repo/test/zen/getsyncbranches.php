#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getSyncBranches();
timeout=0
cid=0

- 执行repoZenTest模块的getSyncBranchesTest方法，参数是$gitRepo, $branchID  @6
- 执行repoZenTest模块的getSyncBranchesTest方法，参数是$emptyRepo, $branchID2  @0
- 执行repoZenTest模块的getSyncBranchesTest方法，参数是$svnRepo, $branchID3  @0
- 执行repoZenTest模块的getSyncBranchesTest方法，参数是null, $branchID4  @0
- 执行repoZenTest模块的getSyncBranchesTest方法，参数是$gitlabRepo, $branchID5  @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zenData('repo')->gen(10);

su('admin');

$repoZenTest = new repoZenTest();

// 测试步骤1：Git仓库正常获取同步分支情况
$gitRepo = new stdClass();
$gitRepo->SCM = 'Git';
$gitRepo->id = 1;
$gitRepo->name = 'test-repo';
$branchID = '';
r(count($repoZenTest->getSyncBranchesTest($gitRepo, $branchID))) && p() && e('6');

// 测试步骤2：空仓库获取同步分支情况
$emptyRepo = new stdClass();
$emptyRepo->SCM = 'Git';
$emptyRepo->id = 2;
$emptyRepo->name = 'empty-repo';
$emptyRepo->isEmpty = true;
$branchID2 = '';
r(count($repoZenTest->getSyncBranchesTest($emptyRepo, $branchID2))) && p() && e('0');

// 测试步骤3：非Git类型仓库获取同步分支情况
$svnRepo = new stdClass();
$svnRepo->SCM = 'Subversion';
$svnRepo->id = 3;
$svnRepo->name = 'svn-repo';
$branchID3 = '';
r(count($repoZenTest->getSyncBranchesTest($svnRepo, $branchID3))) && p() && e('0');

// 测试步骤4：无效参数获取同步分支情况
$branchID4 = '';
r(count($repoZenTest->getSyncBranchesTest(null, $branchID4))) && p() && e('0');

// 测试步骤5：带有cookie的分支ID获取同步分支情况
$_COOKIE['syncBranch'] = 'develop';
$gitlabRepo = new stdClass();
$gitlabRepo->SCM = 'Gitlab';
$gitlabRepo->id = 4;
$gitlabRepo->name = 'gitlab-repo';
$branchID5 = '';
r(count($repoZenTest->getSyncBranchesTest($gitlabRepo, $branchID5))) && p() && e('5');