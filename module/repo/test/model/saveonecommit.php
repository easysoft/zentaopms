#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->saveOneCommit();
timeout=0
cid=18098

- 保存版本库1 一条commit
 - 属性revision @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
 - 属性commit @1
- version初始为6保存版本库1 一条commit
 - 属性revision @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
 - 属性commit @6
- 指定分支名保存版本库1 一条commit
 - 属性repo @1
 - 属性branch @branch1

*/

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repofiles`');
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
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repofiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` int unsigned NOT NULL DEFAULT 0,
  `path` varchar(255) NOT NULL DEFAULT '',
  `parent` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(30) NOT NULL DEFAULT '',
  `action` varchar(2) NOT NULL DEFAULT '',
  `oldPath` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repo = new repoModelTest();

$repoID  = 1;
$version = 1;

r($repo->saveOneCommitTest($repoID, $version)) && p('revision,commit') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a,1'); //保存版本库1 一条commit
$version = 6;
r($repo->saveOneCommitTest($repoID, $version)) && p('revision,commit') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a,6'); //version初始为6保存版本库1 一条commit
$branch = 'branch1';
r($repo->saveOneCommitTest($repoID, $version, $branch)) && p('repo,branch') && e('1,branch1'); //指定分支名保存版本库1 一条commit
