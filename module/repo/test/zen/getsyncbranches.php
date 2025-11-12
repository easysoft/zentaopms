#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getSyncBranches();
timeout=0
cid=0

- 执行repoTest模块的getSyncBranchesTest方法，参数是$svnRepo, $branchID1, array  @0
- 执行repoTest模块的getSyncBranchesTest方法，参数是$gitRepo, $branchID2, array  @0
- 执行repoTest模块的getSyncBranchesTest方法，参数是$gitRepo, $branchID3, $mockBranches2, array  @2
- 执行repoTest模块的getSyncBranchesTest方法，参数是$gitRepo, $branchID4, $mockBranches3, $mockTags, ''  @4
- 执行repoTest模块的getSyncBranchesTest方法，参数是$gitRepo, $branchID5, $mockBranches3, $mockTags, 'develop'  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zendata('repo')->gen(0);

su('admin');

$repoTest = new repoZenTest();

$svnRepo = new stdclass();
$svnRepo->id = 1;
$svnRepo->SCM = 'Subversion';
$svnRepo->name = 'svn-repo';

$gitRepo = new stdclass();
$gitRepo->id = 2;
$gitRepo->SCM = 'Git';
$gitRepo->name = 'git-repo';

$branchID1 = '';
$branchID2 = '';
$branchID3 = '';
$branchID4 = '';
$branchID5 = '';

$mockBranches1 = array();
$mockBranches2 = array('master' => 'master', 'develop' => 'develop', 'feature' => 'feature');
$mockBranches3 = array('master' => 'master', 'develop' => 'develop', 'feature' => 'feature');
$mockTags = array('v1.0', 'v2.0');

r(count($repoTest->getSyncBranchesTest($svnRepo, $branchID1, array(), array(), ''))) && p() && e('0');
r(count($repoTest->getSyncBranchesTest($gitRepo, $branchID2, array(), array(), ''))) && p() && e('0');
r(count($repoTest->getSyncBranchesTest($gitRepo, $branchID3, $mockBranches2, array(), ''))) && p() && e('2');
r(count($repoTest->getSyncBranchesTest($gitRepo, $branchID4, $mockBranches3, $mockTags, ''))) && p() && e('4');
r(count($repoTest->getSyncBranchesTest($gitRepo, $branchID5, $mockBranches3, $mockTags, 'develop'))) && p() && e('3');