#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getCommits();
timeout=0
cid=18052

- 步骤1：获取指定代码库的所有提交记录 @1
- 步骤2：获取指定路径的提交记录 @1
- 步骤3：测试不存在的代码库ID @1
- 步骤4：测试时间范围筛选提交记录 @1
- 步骤5：测试文件类型筛选提交记录 @1
- 步骤6：测试另一个代码库的提交记录 @1
- 步骤7：测试指定版本的提交记录 @1
- 步骤8：SVN 类型代码库获取提交记录 @1
- 步骤9：SVN 类型代码库按路径过滤提交 @1
- 步骤10：SVN 类型代码库单 revision 精准查询 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
// 2. 用户登录
su('admin');

// 3. 创建测试实例
$repoTest = new repoModelTest();

// 4. 执行测试步骤
$result1 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '');
r(is_array($result1)) && p() && e('1'); // 步骤1：获取指定代码库的所有提交记录

$result2 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '/src');
r(is_array($result2)) && p() && e('1'); // 步骤2：获取指定路径的提交记录

$result3 = $repoTest->getCommitsTest((object)array('id' => 999, 'SCM' => 'Git'), '');
r(is_array($result3)) && p() && e('1'); // 步骤3：测试不存在的代码库ID

$result4 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '', 'HEAD', 'dir', null, '2024-01-01', '2024-12-31');
r(is_array($result4)) && p() && e('1'); // 步骤4：测试时间范围筛选提交记录

$result5 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '/src/main.php', 'HEAD', 'file');
r(is_array($result5)) && p() && e('1'); // 步骤5：测试文件类型筛选提交记录

$result6 = $repoTest->getCommitsTest((object)array('id' => 2, 'SCM' => 'Git'), '');
r(is_array($result6)) && p() && e('1'); // 步骤6：测试另一个代码库的提交记录

$result7 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '', 'commit1');
r(is_array($result7)) && p() && e('1'); // 步骤7：测试指定版本的提交记录

$svnRepo = (object)array('id' => 1, 'SCM' => 'Subversion', 'scmType' => 'svn');
$result8 = $repoTest->getCommitsTest($svnRepo, '');
r(is_array($result8)) && p() && e('1'); // 步骤8：SVN 类型代码库获取提交记录

$result9 = $repoTest->getCommitsTest($svnRepo, '/trunk');
r(is_array($result9)) && p() && e('1'); // 步骤9：SVN 类型代码库按路径过滤提交

$singleQuery = new stdclass();
$singleQuery->commit = '1';
$result10 = $repoTest->getCommitsTest($svnRepo, '', '1', 'dir', null, '', '', $singleQuery);
r(is_array($result10)) && p() && e('1'); // 步骤10：SVN 类型代码库单 revision 精准查询
