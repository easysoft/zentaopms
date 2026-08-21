#!/usr/bin/env php
<?php

/**

title=测试 repoModel::saveCommit();
timeout=0
cid=18094

- 步骤1：正常Git提交保存 @3
- 步骤2：SVN提交数量验证 @2
- 步骤2：SVN文件记录验证 @1
- 步骤3：空数据处理 @0
- 步骤4：重复提交跳过 @0
- 步骤5：分支信息保存 @2
- 步骤6：大批量提交处理 @10
- 步骤7：异常数据容错 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
zenData('ops_repo')->gen(0);
zenData('ops_repohistory')->gen(0);
zenData('ops_repobranch')->gen(0);
zenData('ops_repofiles')->gen(0);

$repo = zenData('ops_repo');
$repo->id->range('1-10');
$repo->spaceID->range('1{10}');
$repo->product->range('1{10}');
$repo->name->range('repo1,repo2,repo3,repo4,repo5,repo6,repo7,repo8,repo9,repo10');
$repo->gitUID->range('uid1,uid2,uid3,uid4,uid5,uid6,uid7,uid8,uid9,uid10');
$repo->status->range('active{10}');
$repo->deleted->range('0{10}');
$repo->gen(10);

// 使用管理员身份登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试步骤1：正常保存Git仓库提交数据
r($repoTest->saveCommitWithMockDataTest(1, 'Git', 1)) && p() && e('3'); // 步骤1：正常Git提交保存

// 测试步骤2：正常保存SVN仓库提交数据并验证文件记录
r($repoTest->saveCommitWithMockDataCountTest(2, 'Subversion', 1)) && p() && e('2'); // 步骤2：SVN提交数量验证
r($repoTest->saveCommitWithMockDataFilesCountGreaterThanTest(2, 'Subversion', 1, 0)) && p() && e('1'); // 步骤2：SVN文件记录验证

// 测试步骤3：测试空提交数据处理
r($repoTest->saveCommitWithEmptyDataTest(3)) && p() && e('0'); // 步骤3：空数据处理

// 测试步骤4：测试重复版本号处理逻辑
r($repoTest->saveCommitWithMockDataTest(1, 'Git', 1)) && p() && e('0'); // 步骤4：重复提交跳过

// 测试步骤5：测试带分支信息的提交保存
r($repoTest->saveCommitWithBranchTest(4, 'Git', 1, 'develop')) && p() && e('2'); // 步骤5：分支信息保存

// 测试步骤6：测试大批量提交数据处理
r($repoTest->saveCommitWithLargeDataTest(5, 'Git', 1)) && p() && e('10'); // 步骤6：大批量提交处理

// 测试步骤7：测试异常数据的容错处理
r($repoTest->saveCommitWithInvalidDataTest(6, 'Git', 1)) && p() && e('1'); // 步骤7：异常数据容错
