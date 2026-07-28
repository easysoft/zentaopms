#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->getGitFoxRepos();
timeout=0
cid=0

- 正常调用返回数组 >> 1
- 返回结果包含未删除且非importing状态的repo1 >> 1
- 返回结果包含repo2 >> 1
- 返回结果包含repo3 >> 1
- importing状态的repo4不在结果中 >> 1
- 已删除的repo5不在结果中 >> 1
- 返回数组数量 >= 3 >> 1

*/

$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'repo1', 'scmType' => 'git', 'gitUID' => 'uid1', 'acl' => 'open', 'status' => 'active',    'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'repo2', 'scmType' => 'git', 'gitUID' => 'uid2', 'acl' => 'open', 'status' => 'active',    'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'repo3', 'scmType' => 'git', 'gitUID' => 'uid3', 'acl' => 'open', 'status' => 'active',    'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '1', 'name' => 'repo4', 'scmType' => 'git', 'gitUID' => 'uid4', 'acl' => 'open', 'status' => 'importing', 'deleted' => 0),
    array('id' => 5, 'spaceID' => 1, 'product' => '1', 'name' => 'repo5', 'scmType' => 'git', 'gitUID' => 'uid5', 'acl' => 'open', 'status' => 'active',    'deleted' => 1),
);
foreach($repos as $repoData) $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();

$repoTest = new repoModelTest();
r($repoTest->getGitFoxReposTest()) && p('1')     && e('repo1');
r($repoTest->getGitFoxReposTest()) && p('2')     && e('repo2');
r($repoTest->getGitFoxReposTest()) && p('3')     && e('repo3');
r($repoTest->getGitFoxReposTest()) && p('4,5')   && e('~~,~~');
r($repoTest->getGitFoxReposTest()) && p('1,2,3') && e('repo1,repo2,repo3');
