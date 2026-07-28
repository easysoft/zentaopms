#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel::deleteRepo();
timeout=0
cid=18041

- 删除gitlab版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0
- 删除gitea版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0
- 删除不存在的版本库仍然清除关联数据 >> 1
- 删除已删除版本库仍然清除关联数据 >> 1
- 删除第三个版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0

*/

zenData('ops_repofiles')->gen(0);
zenData('ops_repobranch')->gen(0);
zenData('ops_repohistory')->gen(0);

$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint unsigned NOT NULL DEFAULT 0,
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'repo1', 'scmType' => 'git', 'gitUID' => 'uid1', 'providerID' => 0, 'mirror' => 0, 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'repo3', 'scmType' => 'git', 'gitUID' => 'uid3', 'providerID' => 1, 'mirror' => 1, 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 5, 'spaceID' => 1, 'product' => '1', 'name' => 'repo5', 'scmType' => 'git', 'gitUID' => 'uid5', 'providerID' => 2, 'mirror' => 0, 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData) $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();

$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('repo' => 1, 'revision' => 'r1', 'commit' => 1, 'comment' => 'commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 1, 'revision' => 1, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 1, 'revision' => 1, 'parent' => '/', 'path' => '/file1'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('repo' => 3, 'revision' => 'r3', 'commit' => 1, 'comment' => 'commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 3, 'revision' => 2, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 3, 'revision' => 2, 'parent' => '/', 'path' => '/file3'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('repo' => 5, 'revision' => 'r5', 'commit' => 1, 'comment' => 'commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 5, 'revision' => 3, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 5, 'revision' => 3, 'parent' => '/', 'path' => '/file5'))->exec();

$repoTest = new repoModelTest();
r($repoTest->deleteRepoTest(1)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(3)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(999)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(1)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(5)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
