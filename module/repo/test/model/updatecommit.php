#!/usr/bin/env php
<?php

/**

title=测试 repoModel::updateCommit();
timeout=0
cid=18110

- 步骤1：测试Gitlab类型代码库更新 @1
- 步骤2：测试Git类型代码库更新，期望返回2条历史记录 @2
- 步骤3：测试SVN类型代码库更新，期望返回2条历史记录 @2
- 步骤4：测试无效代码库ID @0
- 步骤5：测试不存在的代码库ID @0
- 步骤6：测试带分支参数的Git更新属性result @1
- 步骤7：测试带objectID参数的更新属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repouser`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `serviceHost` varchar(255) NOT NULL DEFAULT '',
  `serviceProject` varchar(255) NOT NULL DEFAULT '',
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
  `commit` int NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `committer` varchar(255) NOT NULL DEFAULT '',
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'GitlabRepo', 'path' => 'http://repo.local/gitlab', 'SCM' => 'Gitlab', 'scmType' => 'git', 'serviceHost' => '1', 'serviceProject' => '101', 'gitUID' => 'uid1', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'GitRepo',    'path' => 'http://repo.local/git',    'SCM' => 'Git',    'scmType' => 'git', 'serviceHost' => '1', 'serviceProject' => '102', 'gitUID' => 'uid2', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'SVNRepo',    'path' => 'https://svn.qc.oop.cc/svn/unittest/', 'SCM' => 'Subversion', 'scmType' => 'svn', 'serviceHost' => '0', 'serviceProject' => '', 'gitUID' => 'uid3', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '1', 'name' => 'TestRepo',   'path' => 'http://repo.local/test',   'SCM' => 'Git',    'scmType' => 'git', 'serviceHost' => '1', 'serviceProject' => '104', 'gitUID' => 'uid4', 'acl' => 'private', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData)
{
    $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoData['id'], 'account' => 'admin'))->exec();
}

$histories = array(
    array('repo' => 1, 'revision' => 'abc123', 'commit' => 1, 'comment' => 'Initial commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'),
    array('repo' => 2, 'revision' => 'def456', 'commit' => 1, 'comment' => 'Add feature',    'committer' => 'user1', 'time' => '2024-01-02 10:00:00'),
    array('repo' => 2, 'revision' => 'ghi789', 'commit' => 2, 'comment' => 'Fix bug',        'committer' => 'user2', 'time' => '2024-01-03 10:00:00'),
    array('repo' => 3, 'revision' => '1',      'commit' => 1, 'comment' => 'Add file',       'committer' => 'dev1',  'time' => '2024-01-04 10:00:00'),
    array('repo' => 3, 'revision' => '2',      'commit' => 2, 'comment' => 'Update docs',    'committer' => 'dev2',  'time' => '2024-01-05 10:00:00'),
);
foreach($histories as $history) $tester->dao->insert(TABLE_REPOHISTORY)->data((object)$history)->exec();

su('admin');

$repoTest = new repoModelTest();
foreach($repos as $repoData)
{
    $repoID = $repoData['id'];
    $repoTest->setGitfoxRepoCache($repoID, (object)array(
        'id'     => $repoID,
        'path'   => "space/repo{$repoID}",
        'gitURL' => "http://gitfox.local/space/repo{$repoID}.git",
    ));
}

r($repoTest->updateCommitTest(1)) && p() && e('1');
r($repoTest->updateCommitTest(2)) && p() && e('2');
r($repoTest->updateCommitTest(3)) && p() && e('2');
r($repoTest->updateCommitTest(999)) && p() && e('0');
r($repoTest->updateCommitTest(0)) && p() && e('0');
r($repoTest->updateCommitTest(2, 0, 'main')) && p('result') && e('1');
r($repoTest->updateCommitTest(2, 123)) && p('result') && e('1');
