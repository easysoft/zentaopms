#!/usr/bin/env php
<?php

/**

title=测试 repoModel::fixCommit();
timeout=0
cid=18045

- 测试步骤1：正常情况下修复repo3的第一条记录第3条的commit属性 @1
- 测试步骤2：空repo历史记录情况 @0
- 测试步骤3：不存在的repoID测试 @0
- 测试步骤4：单条历史记录情况第8条的commit属性 @1
- 测试步骤5：两条历史记录按时间排序验证第一条第1条的commit属性 @1
- 测试步骤6：验证repo5的第一条记录修复结果第9条的commit属性 @1
- 测试步骤7：验证repo1的第二条记录commit序号第2条的commit属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repobranch`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repohistory`');
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

$histories = array(
    array('id' => 1,  'repo' => 1, 'revision' => 'a1b2c3', 'commit' => 100,  'comment' => 'Initial commit', 'committer' => 'user1',   'time' => '2022-01-01 10:00:00'),
    array('id' => 2,  'repo' => 1, 'revision' => 'd4e5f6', 'commit' => 200,  'comment' => 'Fix bug',        'committer' => 'user2',   'time' => '2022-01-01 11:00:00'),
    array('id' => 3,  'repo' => 3, 'revision' => 'j1k2l3', 'commit' => 300,  'comment' => 'Update docs',    'committer' => 'admin',   'time' => '2022-01-01 13:00:00'),
    array('id' => 4,  'repo' => 3, 'revision' => 'm4n5o6', 'commit' => 400,  'comment' => 'Refactor code',  'committer' => 'dev1',    'time' => '2022-01-01 14:00:00'),
    array('id' => 5,  'repo' => 3, 'revision' => 'p7q8r9', 'commit' => 500,  'comment' => 'Fix test',       'committer' => 'dev2',    'time' => '2022-01-01 15:00:00'),
    array('id' => 6,  'repo' => 3, 'revision' => 's1t2u3', 'commit' => 600,  'comment' => 'Add test',       'committer' => 'tester1', 'time' => '2022-01-01 16:00:00'),
    array('id' => 7,  'repo' => 3, 'revision' => 'v4w5x6', 'commit' => 700,  'comment' => 'Update readme',  'committer' => 'tester2', 'time' => '2022-01-01 17:00:00'),
    array('id' => 8,  'repo' => 4, 'revision' => 'y7z8a9', 'commit' => 800,  'comment' => 'Fix style',      'committer' => 'pm1',     'time' => '2022-01-01 18:00:00'),
    array('id' => 9,  'repo' => 5, 'revision' => 'b1c2d3', 'commit' => 900,  'comment' => 'Final commit',   'committer' => 'admin',   'time' => '2022-01-01 19:00:00'),
    array('id' => 10, 'repo' => 5, 'revision' => 'final',  'commit' => 1000, 'comment' => 'Last commit',    'committer' => 'dev3',    'time' => '2022-01-01 20:00:00'),
);
foreach($histories as $history) $tester->dao->insert(TABLE_REPOHISTORY)->data((object)$history)->exec();

su('admin');

$repoTest = new repoModelTest();

r($repoTest->fixCommitTest(3)) && p('3:commit') && e('1'); // 测试步骤1：正常情况下修复repo3的第一条记录
r($repoTest->fixCommitTest(2)) && p() && e('0'); // 测试步骤2：空repo历史记录情况
r($repoTest->fixCommitTest(999)) && p() && e('0'); // 测试步骤3：不存在的repoID测试
r($repoTest->fixCommitTest(4)) && p('8:commit') && e('1'); // 测试步骤4：单条历史记录情况
r($repoTest->fixCommitTest(1)) && p('1:commit') && e('1'); // 测试步骤5：两条历史记录按时间排序验证第一条
r($repoTest->fixCommitTest(5)) && p('9:commit') && e('1'); // 测试步骤6：验证repo5的第一条记录修复结果
r($repoTest->fixCommitTest(1)) && p('2:commit') && e('2'); // 测试步骤7：验证repo1的第二条记录commit序号
