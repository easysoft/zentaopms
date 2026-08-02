#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel::updateCommitDate();
timeout=0
cid=18112
- 步骤1：有效版本库调用真实 API @1
- 步骤2：GitFox 版本库调用真实 API @1
- 步骤3：不存在的版本库不报错 @1
- 步骤4：SVN 版本库不报错 @1
- 步骤5：无效版本库 ID 不报错 @1

*/

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repouser`');
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
  `account` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `encrypt` varchar(30) NOT NULL DEFAULT 'base64',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'private',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  `lastCommit` datetime DEFAULT NULL,
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

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'testHtml', 'path' => 'http://repo.local/testhtml', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'project1', 'path' => 'http://repo.local/project1', 'SCM' => 'Gitlab', 'scmType' => 'git', 'gitUID' => 'uid2', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'unittest', 'path' => 'http://repo.local/unittest', 'SCM' => 'GitFox', 'scmType' => 'git', 'gitUID' => 'uid3', 'acl' => 'private', 'status' => 'active', 'deleted' => 0, 'lastCommit' => '2024-01-01 00:00:00'),
    array('id' => 4, 'spaceID' => 1, 'product' => '1', 'name' => 'testSvn', 'path' => 'https://svn.qc.oop.cc/svn/unittest/', 'SCM' => 'Subversion', 'scmType' => 'svn', 'account' => 'admin', 'password' => 'encoded', 'encrypt' => 'base64', 'gitUID' => 'uid4', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData) $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();

foreach(range(1, 4) as $repoID)
{
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoID, 'account' => 'admin'))->exec();
}

$repo = new repoModelTest();
$repo->seedGitFoxEntry();


r($repo->updateCommitDateSuccessTest(1)) && p() && e('1');
r($repo->updateCommitDateSuccessTest(3)) && p() && e('1');
r($repo->updateCommitDateSuccessTest(999)) && p() && e('1');
r($repo->updateCommitDateSuccessTest(4)) && p() && e('1');
r($repo->updateCommitDateSuccessTest(0)) && p() && e('1');
