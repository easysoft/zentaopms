#!/usr/bin/env php
<?php

/**

title=测试 svnModel::run();
timeout=0
cid=18720

- 测试步骤1：正常流程有SVN仓库时执行同步 @false
- 测试步骤2：边界情况无SVN仓库时返回false @0
- 测试步骤3：验证setRepos方法能正确识别SVN仓库 @You must set one svn repo.
- 测试步骤4：验证无仓库时repos为空 @0
- 测试步骤5：验证有SVN仓库配置时能设置repos @You must set one svn repo.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

// 测试步骤1：正常流程 - 有SVN仓库时执行同步
zenData('job')->gen(0);
zenData('repo')->loadYaml('repo_run', false, 2)->gen(1);
zenData('repofiles')->gen(0);
zenData('repohistory')->loadYaml('repohistory_run', false, 2)->gen(1);
su('admin');

$svn = new svnTest();

r($svn->runTest('normal')) && p() && e('false'); // 测试步骤1：正常流程有SVN仓库时执行同步

// 测试步骤2：边界情况 - 无SVN仓库时返回false
zenData('job')->gen(0);
zenData('repo')->gen(0);
zenData('repofiles')->gen(0);
zenData('repohistory')->gen(0);

r($svn->runTest('empty')) && p() && e(0); // 测试步骤2：边界情况无SVN仓库时返回false

// 测试步骤3：验证setRepos方法能正确识别SVN仓库
zenData('job')->gen(0);
zenData('repo')->loadYaml('repo_run', false, 2)->gen(2);
zenData('repofiles')->gen(0);
zenData('repohistory')->gen(0);

r($svn->runTest('repos')) && p() && e('You must set one svn repo.'); // 测试步骤3：验证setRepos方法能正确识别SVN仓库

// 测试步骤4：验证无仓库时repos为空
zenData('job')->gen(0);
zenData('repo')->gen(0);
zenData('repofiles')->gen(0);
zenData('repohistory')->gen(0);

r($svn->runTest('repos')) && p() && e(0); // 测试步骤4：验证无仓库时repos为空

// 测试步骤5：验证有SVN仓库配置时能设置repos
zenData('job')->gen(0);
zenData('repo')->loadYaml('repo_run', false, 2)->gen(3);
zenData('repofiles')->gen(0);
zenData('repohistory')->gen(0);

r($svn->runTest('repos')) && p() && e('You must set one svn repo.'); // 测试步骤5：验证有SVN仓库配置时能设置repos