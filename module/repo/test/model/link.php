#!/usr/bin/env php
<?php

/**

title=测试 repoModel::link();
timeout=0
cid=18086

- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $validLinks 第0条的relation属性 @commit
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $validLinks 第0条的BType属性 @story
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $validLinks 第0条的AType属性 @revision
- 执行repoTest模块的linkTest方法，参数是1, $invalidRevision, 'story', 'repo', $validLinks  @失败
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $emptyLinks  @0
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'commit', $validLinks 第0条的relation属性 @commit
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'task', 'repo', $validLinks 第0条的BType属性 @task

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repouser`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repohistory`');
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

$tester->dao->delete()->from(TABLE_RELATION)->where('AID')->in('1,101,102')->orWhere('BID')->in('1,101,102')->exec();
$tester->dao->delete()->from(TABLE_STORY)->where('id')->in('101,102')->exec();
$tester->dao->delete()->from(TABLE_TASK)->where('id')->in('101,102')->exec();

$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'repo1', 'path' => 'http://repo.local/repo1', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => 1, 'account' => 'admin'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('id' => 1, 'repo' => 1, 'revision' => 'c808480afe22d3a55d94e91c59a8f3170212ade0', 'commit' => 1, 'comment' => 'Initial commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();

$tester->dao->insert(TABLE_STORY)->data((object)array('id' => 101, 'product' => 1, 'title' => 'Story 101', 'type' => 'story', 'status' => 'active', 'stage' => 'wait', 'version' => 1, 'vision' => 'rnd', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_STORY)->data((object)array('id' => 102, 'product' => 1, 'title' => 'Story 102', 'type' => 'story', 'status' => 'active', 'stage' => 'wait', 'version' => 1, 'vision' => 'rnd', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_TASK)->data((object)array('id' => 101, 'project' => 1, 'execution' => 1, 'name' => 'Task 101', 'type' => 'devel', 'status' => 'wait', 'version' => 1, 'vision' => 'rnd', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_TASK)->data((object)array('id' => 102, 'project' => 1, 'execution' => 1, 'name' => 'Task 102', 'type' => 'devel', 'status' => 'wait', 'version' => 1, 'vision' => 'rnd', 'deleted' => 0))->exec();

$validRevision = 'c808480afe22d3a55d94e91c59a8f3170212ade0';
$invalidRevision = '22222';
$validLinks = array(101, 102);
$emptyLinks = array();

su('admin');

$repoTest = new repoModelTest();
$repoTest->setGitfoxRepoCache(1, (object)array('id' => 1, 'path' => 'space/repo1', 'gitURL' => 'http://gitfox.local/space/repo1.git'));
$repoTest->resetHttpClient();

r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $validLinks)) && p('0:relation') && e('commit');
r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $validLinks)) && p('0:BType') && e('story');
r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $validLinks)) && p('0:AType') && e('revision');
r($repoTest->linkTest(1, $invalidRevision, 'story', 'repo', $validLinks)) && p('') && e('失败');
r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $emptyLinks)) && p('') && e('0');
r($repoTest->linkTest(1, $validRevision, 'story', 'commit', $validLinks)) && p('0:relation') && e('commit');
r($repoTest->linkTest(1, $validRevision, 'task', 'repo', $validLinks)) && p('0:BType') && e('task');

$repoTest->restoreHttpClient();
