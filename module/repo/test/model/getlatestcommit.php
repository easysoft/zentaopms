#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getLatestCommit();
timeout=0
cid=18067

- 执行repo模块的getLatestCommitTest方法，参数是1
 - 属性id @1
 - 属性revision @c808480afe22d3a55d94e91c59a8f3170212ade0
- 执行repo模块的getLatestCommitTest方法，参数是3
 - 属性id @2
 - 属性commit @2
- 执行repo模块的getLatestCommitTest方法，参数是2  @0
- 执行repo模块的getLatestCommitTest方法，参数是4
 - 属性id @6
 - 属性revision @3
- 执行repo模块的getLatestCommitTestWithoutCount方法，参数是1
 - 属性id @1
 - 属性revision @c808480afe22d3a55d94e91c59a8f3170212ade0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repobranch`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
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
  PRIMARY KEY (`id`)
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

$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'repo1', 'SCM' => 'Gitlab', 'status' => 'active', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'repo2', 'SCM' => 'Git', 'status' => 'active', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'repo3', 'SCM' => 'Git', 'status' => 'active', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 4, 'spaceID' => 1, 'product' => '1', 'name' => 'repo4', 'SCM' => 'Subversion', 'status' => 'active', 'deleted' => 0))->exec();

$histories = array(
    array('id' => 1, 'repo' => 1, 'revision' => 'c808480afe22d3a55d94e91c59a8f3170212ade0', 'commit' => 1, 'comment' => 'repo1 commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'),
    array('id' => 2, 'repo' => 3, 'revision' => 'd30919bdb9b4cf8e2698f4a6a30e41910427c01c', 'commit' => 1, 'comment' => 'repo3 latest', 'committer' => 'admin', 'time' => '2024-01-02 10:00:00'),
    array('id' => 3, 'repo' => 3, 'revision' => '0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb', 'commit' => 1, 'comment' => 'repo3 older', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'),
    array('id' => 6, 'repo' => 4, 'revision' => '3', 'commit' => 1, 'comment' => 'svn latest', 'committer' => 'admin', 'time' => '2024-01-03 10:00:00'),
);
foreach($histories as $history) $tester->dao->insert(TABLE_REPOHISTORY)->data((object)$history)->exec();

$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 1, 'revision' => 1, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 3, 'revision' => 2, 'branch' => 'develop'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 3, 'revision' => 3, 'branch' => 'feature'))->exec();

su('admin');

$repo = new repoModelTest();

r($repo->getLatestCommitTest(1)) && p('id,revision') && e('1,c808480afe22d3a55d94e91c59a8f3170212ade0');
r($repo->getLatestCommitTest(3)) && p('id,commit') && e('2,2');
r($repo->getLatestCommitTest(2)) && p() && e('0');
r($repo->getLatestCommitTest(4)) && p('id,revision') && e('6,3');
r($repo->getLatestCommitTestWithoutCount(1)) && p('id,revision') && e('1,c808480afe22d3a55d94e91c59a8f3170212ade0');
