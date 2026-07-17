#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getFileCommits();
timeout=0
cid=18056

- 获取代码文件得提交信息
 - 第0条的revision属性 @c808480afe22d3a55d94e91c59a8f3170212ade0
 - 第0条的date属性 @2023-12-13 19:00:25
- 获取操作为删除文件得提交信息 @0
- 获取svn代码库得提交信息
 - 第0条的revision属性 @1
 - 第0条的comment属性 @+ Add file.

*/

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repouser`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repobranch`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repofiles`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `prefix` varchar(255) NOT NULL DEFAULT '',
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
  `revision` varchar(255) NOT NULL DEFAULT '',
  `comment` text DEFAULT NULL,
  `committer` varchar(255) NOT NULL DEFAULT '',
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
  `path` varchar(255) NOT NULL DEFAULT '',
  `parent` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(30) NOT NULL DEFAULT 'file',
  `action` char(1) NOT NULL DEFAULT 'A',
  `oldPath` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'gitlabRepo', 'path' => 'http://repo.local/gitlab', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'giteaRepo',  'path' => 'http://repo.local/gitea',  'SCM' => 'Gitea',  'scmType' => 'git', 'gitUID' => 'uid3', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '1', 'name' => 'svnRepo',    'path' => 'https://svn.qc.oop.cc/svn/unittest/', 'SCM' => 'Subversion', 'scmType' => 'svn', 'gitUID' => 'uid4', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData)
{
    $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoData['id'], 'account' => 'admin'))->exec();
}

$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('id' => 1, 'repo' => 1, 'revision' => 'c808480afe22d3a55d94e91c59a8f3170212ade0', 'comment' => 'Add license', 'committer' => 'admin', 'time' => '2023-12-13 19:00:25'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('id' => 2, 'repo' => 3, 'revision' => 'deleted-commit', 'comment' => 'Delete file', 'committer' => 'admin', 'time' => '2023-12-14 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('id' => 3, 'repo' => 4, 'revision' => '1', 'comment' => '+ Add file.', 'committer' => 'admin', 'time' => '2023-12-14 11:00:00'))->exec();

$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 1, 'revision' => 1, 'branch' => 'branch3'))->exec();

$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 1, 'revision' => 1, 'path' => '/LICENSE', 'parent' => '/', 'type' => 'file', 'action' => 'A', 'oldPath' => ''))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 3, 'revision' => 2, 'path' => '/deleted.txt', 'parent' => '/', 'type' => 'file', 'action' => 'D', 'oldPath' => ''))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 4, 'revision' => 3, 'path' => '/trunk/zentaoext/zentaopro/cmmi/db/README.md', 'parent' => '/trunk/zentaoext/zentaopro/cmmi/db', 'type' => 'file', 'action' => 'A', 'oldPath' => ''))->exec();

$repo = new repoModelTest();
foreach($repos as $repoData)
{
    $repoID = $repoData['id'];
    $repo->setGitfoxRepoCache($repoID, (object)array(
        'id'     => $repoID,
        'path'   => "space/repo{$repoID}",
        'gitURL' => "http://gitfox.local/space/repo{$repoID}.git",
    ));
}

$parent = '/trunk/zentaoext/zentaopro/cmmi/db';

r($repo->getFileCommitsTest(1, 'branch3')) && p('0:revision,date')    && e('c808480afe22d3a55d94e91c59a8f3170212ade0,2023-12-13 19:00:25');
r($repo->getFileCommitsTest(3, ''))        && p()                     && e('0');
r($repo->getFileCommitsTest(4, '', $parent)) && p('0:revision,comment') && e('1,+ Add file.');
