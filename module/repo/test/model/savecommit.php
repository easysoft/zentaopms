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
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repofiles`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repobranch`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(500) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `projects` varchar(255) NOT NULL DEFAULT '',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `forkID` int unsigned DEFAULT NULL,
  `mirror` tinyint(1) NOT NULL DEFAULT 0,
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `connector` text DEFAULT NULL,
  `defaultBranch` varchar(255) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime DEFAULT NULL,
  `deleted` tinyint NOT NULL DEFAULT 0,
  `synced` tinyint unsigned NOT NULL DEFAULT 0,
  `branchArchivable` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repohistory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` varchar(40) NOT NULL DEFAULT '',
  `commit` int unsigned NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `committer` varchar(100) NOT NULL DEFAULT '',
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `repo` (`repo`),
  KEY `revision` (`revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repobranch` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` int unsigned NOT NULL DEFAULT 0,
  `branch` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repofiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` int unsigned NOT NULL DEFAULT 0,
  `path` varchar(255) NOT NULL DEFAULT '',
  `parent` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(30) NOT NULL DEFAULT '',
  `action` varchar(2) NOT NULL DEFAULT '',
  `oldPath` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `repo_revision` (`repo`, `revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

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
