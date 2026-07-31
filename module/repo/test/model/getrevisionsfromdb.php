#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getRevisionsFromDB();
timeout=0
cid=18079

- 获取版本库提交记录第0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb条的id属性 @3
- 获取limit为1版本库提交记录数量 @1
- 获取maxrevision的列表第0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb条的commit属性 @1
- 获取maxrevision的列表数量 @1
- 获取minrevision的列表第d30919bdb9b4cf8e2698f4a6a30e41910427c01c条的commit属性 @2
- 获取minrevision的列表数量 @1

*/

global $tester;
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
  KEY `repo_revision` (`repo`, `revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$histories = array(
    array('id' => 1, 'repo' => 3, 'revision' => '2e0dd521b4a29930d5670a2c142a4400d7cffc1a', 'commit' => 3, 'comment' => 'commit3', 'committer' => 'admin', 'time' => '2024-01-03 10:00:00'),
    array('id' => 2, 'repo' => 3, 'revision' => 'd30919bdb9b4cf8e2698f4a6a30e41910427c01c', 'commit' => 2, 'comment' => 'commit2', 'committer' => 'admin', 'time' => '2024-01-02 10:00:00'),
    array('id' => 3, 'repo' => 3, 'revision' => '0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb', 'commit' => 1, 'comment' => 'commit1', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'),
);
foreach($histories as $history) $tester->dao->insert(TABLE_REPOHISTORY)->data((object)$history)->exec();

$repo = new repoModelTest();

$repoID      = 3;
$limit       = 1;
$maxRevision = '0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb';
$minRevision = 'd30919bdb9b4cf8e2698f4a6a30e41910427c01c';

r($repo->getRevisionsFromDBTest($repoID)) && p('0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb:id') && e('3'); //获取版本库提交记录

$result = $repo->getRevisionsFromDBTest($repoID, $limit);
r($repo->getRevisionsFromDBCountTest($repoID, $limit)) && p() && e('1'); //获取limit为1版本库提交记录数量

$result = $repo->getRevisionsFromDBTest($repoID, 0, $maxRevision, '');
r($repo->getRevisionsFromDBTest($repoID, 0, $maxRevision, '')) && p('0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb:commit') && e('1'); // 获取maxrevision的列表
r($repo->getRevisionsFromDBCountTest($repoID, 0, $maxRevision, '')) && p() && e('1'); // 获取maxrevision的列表数量

$result = $repo->getRevisionsFromDBTest($repoID, 0, '', $minRevision);
r($repo->getRevisionsFromDBTest($repoID, 0, '', $minRevision)) && p('d30919bdb9b4cf8e2698f4a6a30e41910427c01c:commit') && e('2'); // 获取minrevision的列表
r($repo->getRevisionsFromDBCountTest($repoID, 0, '', $minRevision)) && p() && e('1'); // 获取minrevision的列表数量
