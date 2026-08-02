#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getHistory();
timeout=0
cid=18064

- 步骤1：正常查询有效revision列表 @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
- 步骤2：查询空revision数组 @empty
- 步骤3：查询不存在的revision @empty
- 步骤4：测试无效repoID参数 @empty
- 步骤5：测试单个revision查询 @2e0dd521b4a29930d5670a2c142a4400d7cffc1a

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

su('admin');
$revisions = array(
    '2e0dd521b4a29930d5670a2c142a4400d7cffc1a',
    'd30919bdb9b4cf8e2698f4a6a30e41910427c01c',
);
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('repo' => 1, 'revision' => $revisions[0], 'commit' => 1, 'comment' => 'commit1', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('repo' => 1, 'revision' => $revisions[1], 'commit' => 2, 'comment' => 'commit2', 'committer' => 'admin', 'time' => '2024-01-02 10:00:00'))->exec();

$repoTest = new repoModelTest();

r($repoTest->getHistoryTest(1, $revisions))                               && p('0') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); // 步骤1：正常查询有效revision列表
r($repoTest->getHistoryTest(1, array()))                                 && p()    && e('empty');                                    // 步骤2：查询空revision数组
r($repoTest->getHistoryTest(1, array('nonexistent123', 'invalid456')))   && p()    && e('empty');                                    // 步骤3：查询不存在的revision
r($repoTest->getHistoryTest(999, $revisions))                            && p()    && e('empty');                                    // 步骤4：测试无效repoID参数
r($repoTest->getHistoryTest(1, array_slice($revisions, 0, 1)))           && p('0') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); // 步骤5：测试单个revision查询
