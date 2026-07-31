#!/usr/bin/env php
<?php

/**

title=测试 repoModel::unlink();
timeout=0
cid=18107

- 执行repoTest模块的unlinkTest方法，参数是1, 'rev001', 'story', 1  @success
- 执行repoTest模块的unlinkTest方法，参数是1, 'rev002', 'bug', 2  @success
- 执行repoTest模块的unlinkTest方法，参数是2, 'rev003', 'task', 3  @success
- 执行repoTest模块的unlinkTest方法，参数是1, 'nonexistent', 'story', 1  @not_found
- 执行repoTest模块的unlinkTest方法，参数是1, 'rev001', 'invalidtype', 1  @no_relation

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
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

$repoTable = zenData('ops_repo');
$repoTable->id->range('1-3');
$repoTable->spaceID->range('1{3}');
$repoTable->product->range('1{3}');
$repoTable->name->range('repo1,repo2,repo3');
$repoTable->gitUID->range('uid1,uid2,uid3');
$repoTable->status->range('active{3}');
$repoTable->deleted->range('0{3}');
$repoTable->gen(3);

global $tester;
$tester->dao->delete()->from(TABLE_REPOHISTORY)->where('repo')->in('1,2,3')->exec();
$tester->dao->delete()->from(TABLE_RELATION)->where('AType')->eq('revision')->andWhere('AID')->in('1,2,3,4,5')->exec();

$histories = array(
    array('id' => 1, 'repo' => 1, 'revision' => 'rev001', 'commit' => 1, 'comment' => '测试提交1', 'committer' => 'admin', 'time' => '2026-07-09 00:00:00'),
    array('id' => 2, 'repo' => 1, 'revision' => 'rev002', 'commit' => 2, 'comment' => '测试提交2', 'committer' => 'admin', 'time' => '2026-07-09 00:00:00'),
    array('id' => 3, 'repo' => 2, 'revision' => 'rev003', 'commit' => 3, 'comment' => '测试提交3', 'committer' => 'admin', 'time' => '2026-07-09 00:00:00'),
    array('id' => 4, 'repo' => 2, 'revision' => 'rev004', 'commit' => 4, 'comment' => '测试提交4', 'committer' => 'admin', 'time' => '2026-07-09 00:00:00'),
    array('id' => 5, 'repo' => 3, 'revision' => 'rev005', 'commit' => 5, 'comment' => '测试提交5', 'committer' => 'admin', 'time' => '2026-07-09 00:00:00')
);
foreach($histories as $history) $tester->dao->insert(TABLE_REPOHISTORY)->data($history)->exec();

$relations = array(
    array('id' => 1, 'AType' => 'revision', 'AID' => 1, 'relation' => 'commit', 'BType' => 'story', 'BID' => 1),
    array('id' => 2, 'AType' => 'revision', 'AID' => 2, 'relation' => 'commit', 'BType' => 'bug',   'BID' => 2),
    array('id' => 3, 'AType' => 'revision', 'AID' => 3, 'relation' => 'commit', 'BType' => 'task',  'BID' => 3),
    array('id' => 4, 'AType' => 'revision', 'AID' => 4, 'relation' => 'commit', 'BType' => 'story', 'BID' => 4),
    array('id' => 5, 'AType' => 'revision', 'AID' => 5, 'relation' => 'commit', 'BType' => 'bug',   'BID' => 5),
    array('id' => 6, 'AType' => 'revision', 'AID' => 3, 'relation' => 'commit', 'BType' => 'task',  'BID' => 6)
);
foreach($relations as $relation) $tester->dao->insert(TABLE_RELATION)->data($relation)->exec();

su('admin');

$repoTest = new repoModelTest();

r($repoTest->unlinkTest(1, 'rev001', 'story', 1)) && p('') && e('success');
r($repoTest->unlinkTest(1, 'rev002', 'bug', 2)) && p('') && e('success');
r($repoTest->unlinkTest(2, 'rev003', 'task', 3)) && p('') && e('success');
r($repoTest->unlinkTest(1, 'nonexistent', 'story', 1)) && p('') && e('not_found');
r($repoTest->unlinkTest(1, 'rev001', 'invalidtype', 1)) && p('') && e('no_relation');