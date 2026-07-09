#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getBranches();
timeout=0
cid=18047

- 步骤1：从SCM获取repo1分支 @0
- 步骤2：从SCM获取repo1分支带标签 @0
- 步骤3：从数据库获取repo1分支属性master @master
- 步骤4：数据库分支带标签前缀属性master @Branch::master
- 步骤5：不存在repo测试 @0
- 步骤6：repo2数据库分支测试属性master @master
- 步骤7：repo2带标签测试属性master @Branch::master

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repobranch`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repouser`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'private',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repobranch` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` int unsigned NOT NULL DEFAULT 0,
  `branch` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'repo1', 'path' => '/tmp/repo1', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'repo2', 'path' => '/tmp/repo2', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid2', 'acl' => 'private', 'status' => 'active', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => 1, 'account' => 'admin'))->exec();
$tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => 2, 'account' => 'admin'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 1, 'revision' => 1, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 1, 'revision' => 2, 'branch' => 'develop'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 2, 'revision' => 3, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 2, 'revision' => 4, 'branch' => 'release'))->exec();

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoModelTest();
$repoTest->setGitfoxRepoCache(1, (object)array('id' => 1, 'path' => 'space/repo1', 'gitURL' => 'http://gitfox.local/space/repo1.git'));
$repoTest->setGitfoxRepoCache(2, (object)array('id' => 2, 'path' => 'space/repo2', 'gitURL' => 'http://gitfox.local/space/repo2.git'));

// 5. 执行测试步骤（至少7个）
r($repoTest->getBranchesTest(1, false, 'scm')) && p() && e('0'); // 步骤1：从SCM获取repo1分支
r($repoTest->getBranchesTest(1, true, 'scm')) && p() && e('0'); // 步骤2：从SCM获取repo1分支带标签
r($repoTest->getBranchesTest(1, false, 'database')) && p('master') && e('master'); // 步骤3：从数据库获取repo1分支
r($repoTest->getBranchesTest(1, true, 'database')) && p('master') && e('Branch::master'); // 步骤4：数据库分支带标签前缀
r($repoTest->getBranchesTest(999, false, 'scm')) && p() && e('0'); // 步骤5：不存在repo测试
r($repoTest->getBranchesTest(2, false, 'database')) && p('master') && e('master'); // 步骤6：repo2数据库分支测试
r($repoTest->getBranchesTest(2, true, 'database')) && p('master') && e('Branch::master'); // 步骤7：repo2带标签测试
