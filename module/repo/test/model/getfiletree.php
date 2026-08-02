#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getFileTree();
timeout=0
cid=18057

- 获取代码文件得提交信息第一个文件
 - 第0条的parent属性 @0
 - 第0条的name属性 @LICENSE
 - 第0条的path属性 @LICENSE
- 获取代码文件得提交信息数量大于1 @1
- 获取svn代码文件得提交信息第一个文件夹信息
 - 第0条的id属性 @dGFn
 - 第0条的name属性 @tag
 - 第0条的parent属性 @0
- 获取svn代码文件得提交信息第一个文件夹信息
 - 第0条的id属性 @dGFnJTJGUkVBRE1FLm1k
 - 第0条的name属性 @README.md
 - 第0条的parent属性 @dGFn
- 获取svn代码文件得提交信息数量 @1

*/

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repouser`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repobranch`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repofiles`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
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
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
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
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repobranch` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` int unsigned NOT NULL DEFAULT 0,
  `branch` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repofiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` int unsigned NOT NULL DEFAULT 0,
  `path` varchar(255) NOT NULL DEFAULT '',
  `parent` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(30) NOT NULL DEFAULT 'file',
  `action` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repos = array(
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'giteaRepo', 'path' => 'http://repo.local/gitea', 'SCM' => 'Gitea', 'scmType' => 'git', 'gitUID' => 'uid3', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '1', 'name' => 'svnRepo',   'path' => 'https://svn.qc.oop.cc/svn/unittest/', 'SCM' => 'Subversion', 'scmType' => 'svn', 'gitUID' => 'uid4', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData)
{
    $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoData['id'], 'account' => 'admin'))->exec();
}

$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('id' => 1, 'repo' => 3, 'revision' => 'git-tree', 'comment' => 'Add files', 'committer' => 'admin', 'time' => '2023-12-13 19:00:25'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('id' => 2, 'repo' => 4, 'revision' => '1', 'comment' => '+ Add file.', 'committer' => 'admin', 'time' => '2023-12-14 11:00:00'))->exec();

$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 3, 'revision' => 1, 'path' => '/LICENSE', 'parent' => '/', 'type' => 'file', 'action' => 'A'))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 3, 'revision' => 1, 'path' => '/README.md', 'parent' => '/', 'type' => 'file', 'action' => 'A'))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 4, 'revision' => 2, 'path' => '/tag/README.md', 'parent' => '/tag', 'type' => 'file', 'action' => 'A'))->exec();

$repo = new repoModelTest();
r($repo->getFileTreeTest(3, '')) && p('0:parent,name,path') && e('0,LICENSE,LICENSE');
r($repo->getFileTreeCountGreaterThanTest(3, '', 1)) && p() && e('1');

r($repo->getFileTreeTest(4, '')) && p('0:id,name,parent') && e('dGFn,tag,0');
r($repo->getFileTreeChildrenTest(4, '', 0)) && p('0:id,name,parent') && e('dGFnJTJGUkVBRE1FLm1k,README.md,dGFn');
r($repo->getFileTreeCountTest(4, '')) && p() && e('1');
