#!/usr/bin/env php
<?php

/**

title=测试 repoModel::saveExistCommits4Branch();
timeout=0
cid=18096

- 测试步骤1：正常情况-保存gitea版本库master分支的历史提交 @1
- 测试步骤2：边界值测试-不存在的仓库ID @0
- 测试步骤3：异常输入-空分支名称 @0
- 测试步骤4：业务逻辑验证-验证保存操作成功 @1
- 测试步骤5：边界条件-仓库没有历史提交记录的分支 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repobranch`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repohistory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` varchar(40) NOT NULL DEFAULT '',
  `commit` int unsigned NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `committer` varchar(100) NOT NULL DEFAULT '',
  `time` datetime DEFAULT NULL,
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

$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('id' => 1, 'repo' => 3, 'revision' => 'r1', 'commit' => 1, 'comment' => 'older commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('id' => 2, 'repo' => 3, 'revision' => 'r2', 'commit' => 2, 'comment' => 'master commit', 'committer' => 'admin', 'time' => '2024-01-02 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('id' => 3, 'repo' => 1, 'revision' => 'r3', 'commit' => 1, 'comment' => 'repo1 commit', 'committer' => 'admin', 'time' => '2024-01-03 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 3, 'revision' => 2, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 1, 'revision' => 3, 'branch' => 'master'))->exec();

su('admin');

$repo = new repoModelTest();

r($repo->saveExistCommits4BranchTest(3, 'master')) && p() && e('1'); // 测试步骤1：正常情况-保存gitea版本库master分支的历史提交
r($repo->saveExistCommits4BranchTest(999, 'master')) && p() && e('0'); // 测试步骤2：边界值测试-不存在的仓库ID
r($repo->saveExistCommits4BranchTest(3, '')) && p() && e('0'); // 测试步骤3：异常输入-空分支名称
r($repo->saveExistCommits4BranchTest(3, 'master')) && p() && e('1'); // 测试步骤4：业务逻辑验证-验证保存操作成功
r($repo->saveExistCommits4BranchTest(1, 'develop')) && p() && e('0'); // 测试步骤5：边界条件-仓库没有历史提交记录的分支
