#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel::updateCommitDate();
timeout=0
cid=18112

- 步骤1：更新Gitlab版本库属性lastCommit @2023-12-23 11:39:02
- 步骤2：GitFox 版本库无提交时保持原 lastCommit @2024-01-01 00:00:00
- 步骤3：不存在的版本库ID @return empty
- 步骤4：SVN版本库（不在同步范围）属性name @testSvn
- 步骤5：无效的版本库ID（0） @return empty

*/

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repouser`');
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
$dbh->exec(<<<'SQL'
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
$repo->setGitfoxRepoCache(1, (object)array('id' => 1, 'path' => 'space/repo1', 'gitURL' => 'http://gitfox.local/space/repo1.git'));
$repo->setGitfoxRepoCache(2, (object)array('id' => 2, 'path' => 'space/repo2', 'gitURL' => 'http://gitfox.local/space/repo2.git'));
$repo->setGitfoxRepoCache(3, (object)array('id' => 3, 'path' => 'space/repo3', 'gitURL' => 'http://gitfox.local/space/repo3.git'));
$repo->setGitfoxRepoCache(4, (object)array('id' => 4, 'path' => 'space/repo4', 'gitURL' => 'http://gitfox.local/space/repo4.git'));

$httpClient = $repo->resetHttpClient();
$httpClient->setResponse('/api/v2/repos/1/commits/list', json_encode((object)array(
    'data' => (object)array(
        'commits' => array((object)array('committed_date' => '2023-12-23T11:39:02+08:00')),
    ),
)));
$httpClient->setResponse('/api/v2/repos/3/commits/list', json_encode((object)array(
    'data' => (object)array('commits' => array()),
)));

r($repo->updateCommitDateTest(1)) && p('lastCommit') && e('2023-12-23 11:39:02'); // 步骤1：更新Gitlab版本库
r($repo->updateCommitDateTest(3)) && p('lastCommit') && e('2024-01-01 00:00:00'); // 步骤2：GitFox 无提交时保持原值
r($repo->updateCommitDateTest(999)) && p() && e('return empty'); // 步骤3：不存在的版本库ID
r($repo->updateCommitDateTest(4)) && p('name') && e('testSvn'); // 步骤4：SVN版本库（不在同步范围）
r($repo->updateCommitDateTest(0)) && p() && e('return empty'); // 步骤5：无效的版本库ID（0）

$repo->restoreHttpClient();
