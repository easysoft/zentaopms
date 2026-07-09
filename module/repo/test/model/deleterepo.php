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

*/

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repofiles`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repobranch`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
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
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repofiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` int unsigned NOT NULL DEFAULT 0,
  `parent` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'repo1', 'gitUID' => 'uid1', 'acl' => 'open', 'status' => 'active', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'repo3', 'gitUID' => 'uid3', 'acl' => 'open', 'status' => 'active', 'deleted' => 0))->exec();
foreach(array(1, 3) as $repoID)
{
    $tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('repo' => $repoID, 'revision' => 'r' . $repoID, 'commit' => 1, 'comment' => 'commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();
    $historyID = $tester->dao->lastInsertID();
    $tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => $repoID, 'revision' => $historyID, 'branch' => 'master'))->exec();
    $tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => $repoID, 'revision' => $historyID, 'parent' => '/', 'path' => '/file' . $repoID))->exec();
}

$repoTest = new repoModelTest();
$gitlabID = 1;
$giteaID  = 3;

r($repoTest->deleteRepoTest($gitlabID)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0'); //删除gitlab版本库
r($repoTest->deleteRepoTest($giteaID))  && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0'); //删除gitea版本库
