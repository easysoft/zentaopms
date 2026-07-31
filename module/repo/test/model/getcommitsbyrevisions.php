#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getCommitsByRevisions();
timeout=0
cid=18054

- 测试步骤1：单个有效版本号 @1
- 测试步骤2：多个有效版本号 @3
- 测试步骤3：不存在的版本号 @0
- 测试步骤4：空版本号数组 @0
- 测试步骤5：混合版本号查询 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repohistory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` varchar(255) NOT NULL DEFAULT '',
  `commit` int NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `committer` varchar(255) NOT NULL DEFAULT '',
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$histories = array(
    array('id' => 1,  'repo' => 1, 'revision' => 'commit001', 'commit' => 1,  'comment' => '提交信息1',  'committer' => 'admin', 'time' => '2026-07-09 00:00:00'),
    array('id' => 2,  'repo' => 1, 'revision' => 'commit002', 'commit' => 2,  'comment' => '提交信息2',  'committer' => 'user1', 'time' => '2026-07-09 00:00:00'),
    array('id' => 3,  'repo' => 1, 'revision' => 'commit003', 'commit' => 3,  'comment' => '提交信息3',  'committer' => 'dev1',  'time' => '2026-07-09 00:00:00'),
    array('id' => 4,  'repo' => 2, 'revision' => 'commit004', 'commit' => 4,  'comment' => '提交信息4',  'committer' => 'admin', 'time' => '2026-07-09 00:00:00'),
    array('id' => 5,  'repo' => 2, 'revision' => 'commit005', 'commit' => 5,  'comment' => '提交信息5',  'committer' => 'user1', 'time' => '2026-07-09 00:00:00'),
    array('id' => 6,  'repo' => 2, 'revision' => 'commit006', 'commit' => 6,  'comment' => '提交信息6',  'committer' => 'dev1',  'time' => '2026-07-09 00:00:00'),
    array('id' => 7,  'repo' => 3, 'revision' => 'commit007', 'commit' => 7,  'comment' => '提交信息7',  'committer' => 'admin', 'time' => '2026-07-09 00:00:00'),
    array('id' => 8,  'repo' => 3, 'revision' => 'commit008', 'commit' => 8,  'comment' => '提交信息8',  'committer' => 'user1', 'time' => '2026-07-09 00:00:00'),
    array('id' => 9,  'repo' => 3, 'revision' => 'commit009', 'commit' => 9,  'comment' => '提交信息9',  'committer' => 'dev1',  'time' => '2026-07-09 00:00:00'),
    array('id' => 10, 'repo' => 3, 'revision' => 'commit010', 'commit' => 10, 'comment' => '提交信息10', 'committer' => 'admin', 'time' => '2026-07-09 00:00:00'),
);
foreach($histories as $history) $tester->dao->insert(TABLE_REPOHISTORY)->data((object)$history)->exec();

su('admin');

$repoTest = new repoModelTest();

$singleRevision = array('commit001');
$multipleRevisions = array('commit002', 'commit003', 'commit005');
$nonExistentRevision = array('commit999', 'commit888');
$emptyRevisions = array();
$mixedRevisions = array('commit004', 'commit999', 'commit006');

r($repoTest->getCommitsByRevisionsTest($singleRevision)) && p() && e('1'); // 测试步骤1：单个有效版本号
r($repoTest->getCommitsByRevisionsTest($multipleRevisions)) && p() && e('3'); // 测试步骤2：多个有效版本号
r($repoTest->getCommitsByRevisionsTest($nonExistentRevision)) && p() && e('0'); // 测试步骤3：不存在的版本号
r($repoTest->getCommitsByRevisionsTest($emptyRevisions)) && p() && e('0'); // 测试步骤4：空版本号数组
r($repoTest->getCommitsByRevisionsTest($mixedRevisions)) && p() && e('2'); // 测试步骤5：混合版本号查询
