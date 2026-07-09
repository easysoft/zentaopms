#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 repoModel::getHistoryRevision();
timeout=0
cid=18065

- 查询提交记录版本号 @d30919bdb9b4cf8e2698f4a6a30e41910427c01c
- 查询提交记录版本号withCommmit参数为true
 - 属性revision @d30919bdb9b4cf8e2698f4a6a30e41910427c01c
 - 属性commit @2
- 查询提交记录版本号withCommmit参数为true
 - 属性revision @0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb
 - 属性commit @1

*/

global $dbh, $tester;
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

$histories = array(
    array('id' => 1, 'repo' => 3, 'revision' => '2e0dd521b4a29930d5670a2c142a4400d7cffc1a', 'commit' => 3, 'comment' => 'commit3', 'committer' => 'admin', 'time' => '2024-01-03 10:00:00'),
    array('id' => 2, 'repo' => 3, 'revision' => 'd30919bdb9b4cf8e2698f4a6a30e41910427c01c', 'commit' => 2, 'comment' => 'commit2', 'committer' => 'admin', 'time' => '2024-01-02 10:00:00'),
    array('id' => 3, 'repo' => 3, 'revision' => '0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb', 'commit' => 1, 'comment' => 'commit1', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'),
);
foreach($histories as $history) $tester->dao->insert(TABLE_REPOHISTORY)->data((object)$history)->exec();

$repoModel = $tester->loadModel('repo');

$repoID     = 3;
$revision   = 'd30919bdb9b4cf8e2698f4a6a30e41910427c01c';
$withCommit = true;
$condition  = 'lt';

r($repoModel->getHistoryRevision($repoID, $revision))                          && p()                  && e('d30919bdb9b4cf8e2698f4a6a30e41910427c01c'); //查询提交记录版本号
r($repoModel->getHistoryRevision($repoID, $revision, $withCommit))             && p('revision,commit') && e('d30919bdb9b4cf8e2698f4a6a30e41910427c01c,2'); //查询提交记录版本号withCommmit参数为true
r($repoModel->getHistoryRevision($repoID, $revision, $withCommit, $condition)) && p('revision,commit') && e('0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb,1'); //查询提交记录版本号withCommmit参数为true
