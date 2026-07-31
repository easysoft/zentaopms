#!/usr/bin/env php
<?php

/**

title=测试 repoModel::markSynced();
timeout=0
cid=18087

- 步骤1：正常代码库ID属性synced @1
- 步骤2：不存在的代码库ID属性synced @0
- 步骤3：边界值0属性synced @0
- 步骤4：负数代码库ID属性synced @0
- 步骤5：验证fixCommit功能的代码库属性synced @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repobranch`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repouser`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
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
  `synced` tinyint unsigned NOT NULL DEFAULT 0,
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
  KEY `repo` (`repo`)
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
CREATE TABLE `ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => '测试代码库1', 'path' => '/test/repo1', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0, 'synced' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => '测试代码库2', 'path' => '/test/repo2', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid2', 'acl' => 'private', 'status' => 'active', 'deleted' => 0, 'synced' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => '测试代码库3', 'path' => '/test/repo3', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid3', 'acl' => 'private', 'status' => 'active', 'deleted' => 0, 'synced' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '1', 'name' => '测试代码库4', 'path' => '/test/repo4', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid4', 'acl' => 'private', 'status' => 'active', 'deleted' => 0, 'synced' => 0),
);
foreach($repos as $repo) $tester->dao->insert(TABLE_REPO)->data((object)$repo)->exec();

foreach(range(1, 4) as $repoID)
{
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoID, 'account' => 'admin'))->exec();
}

$histories = array(
    array('id' => 1, 'repo' => 1, 'revision' => 'r1', 'commit' => 10, 'comment' => 'commit1', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'),
    array('id' => 2, 'repo' => 2, 'revision' => 'r2', 'commit' => 20, 'comment' => 'commit2', 'committer' => 'admin', 'time' => '2024-01-01 12:00:00'),
    array('id' => 3, 'repo' => 2, 'revision' => 'r3', 'commit' => 30, 'comment' => 'commit3', 'committer' => 'admin', 'time' => '2024-01-01 11:00:00'),
);
foreach($histories as $history) $tester->dao->insert(TABLE_REPOHISTORY)->data((object)$history)->exec();

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();
$repoTest->seedGitFoxEntry();

r($repoTest->markSyncedTest(1)) && p('synced') && e('1');    // 步骤1：正常代码库ID
r($repoTest->markSyncedTest(999)) && p('synced') && e('0');  // 步骤2：不存在的代码库ID
r($repoTest->markSyncedTest(0)) && p('synced') && e('0');    // 步骤3：边界值0
r($repoTest->markSyncedTest(-1)) && p('synced') && e('0');   // 步骤4：负数代码库ID
r($repoTest->markSyncedTest(2)) && p('synced') && e('1');    // 步骤5：验证fixCommit功能的代码库
