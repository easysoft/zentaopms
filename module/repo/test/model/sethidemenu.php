#!/usr/bin/env php
<?php

/**

title=测试 repoModel::setHideMenu();
timeout=0
cid=18103

- 步骤1：execution环境下有代码库时返回对象ID @101
- 步骤2：execution环境下无代码库时返回对象ID @102
- 步骤3：project环境下有代码库时返回对象ID @103
- 步骤4：waterfall环境下有代码库时返回对象ID @104
- 步骤5：execution环境下切换其他代码库时返回对象ID @105

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_spaceuser`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_space`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_space` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `code` varchar(50) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `auth` varchar(30) NOT NULL DEFAULT 'extend',
  `desc` varchar(500) NOT NULL DEFAULT '',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime DEFAULT NULL,
  `deleted` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_spaceuser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `space` int unsigned NOT NULL DEFAULT 0,
  `role` varchar(10) NOT NULL DEFAULT '',
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_space_account` (`space`, `account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$tester->dao->delete()->from(TABLE_REPO)->where('id')->in('1,2,3,4,5')->exec();
$tester->dao->delete()->from(TABLE_PROJECT)->where('id')->in('101,102,103,104,105')->exec();
$tester->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->in('101,102,103,104,105')->exec();

$spaces = array(
    array('id' => 1, 'name' => 'space1', 'code' => 'space1', 'acl' => 'open', 'auth' => 'extend', 'createdBy' => 'admin', 'deleted' => 0),
    array('id' => 2, 'name' => 'space2', 'code' => 'space2', 'acl' => 'open', 'auth' => 'extend', 'createdBy' => 'admin', 'deleted' => 0),
    array('id' => 3, 'name' => 'space3', 'code' => 'space3', 'acl' => 'open', 'auth' => 'extend', 'createdBy' => 'admin', 'deleted' => 0),
    array('id' => 4, 'name' => 'space4', 'code' => 'space4', 'acl' => 'open', 'auth' => 'extend', 'createdBy' => 'admin', 'deleted' => 0),
    array('id' => 5, 'name' => 'space5', 'code' => 'space5', 'acl' => 'open', 'auth' => 'extend', 'createdBy' => 'admin', 'deleted' => 0)
);
foreach($spaces as $space) $tester->dao->insert('ops_space')->data($space)->exec();

foreach(range(1, 5) as $spaceID)
{
    $tester->dao->insert('ops_spaceuser')->data((object)array('space' => $spaceID, 'role' => 'manager', 'account' => 'admin'))->exec();
}

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'repo1', 'gitUID' => 'uid1', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '2', 'name' => 'repo2', 'gitUID' => 'uid2', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '3', 'name' => 'repo3', 'gitUID' => 'uid3', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '4', 'name' => 'repo4', 'gitUID' => 'uid4', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 5, 'spaceID' => 1, 'product' => '5', 'name' => 'repo5', 'gitUID' => 'uid5', 'acl' => 'open', 'status' => 'active', 'deleted' => 0)
);
foreach($repos as $repo) $tester->dao->insert(TABLE_REPO)->data($repo)->exec();

$projects = array(
    array('id' => 101, 'name' => '项目1', 'type' => 'project', 'status' => 'doing', 'deleted' => 0),
    array('id' => 102, 'name' => '项目2', 'type' => 'project', 'status' => 'doing', 'deleted' => 0),
    array('id' => 103, 'name' => '项目3', 'type' => 'project', 'status' => 'doing', 'deleted' => 0),
    array('id' => 104, 'name' => '项目4', 'type' => 'project', 'status' => 'doing', 'deleted' => 0),
    array('id' => 105, 'name' => '项目5', 'type' => 'project', 'status' => 'doing', 'deleted' => 0)
);
foreach($projects as $project) $tester->dao->insert(TABLE_PROJECT)->data($project)->exec();
foreach(range(1, 5) as $index)
{
    $tester->dao->insert(TABLE_PROJECTPRODUCT)->data((object)array('project' => 100 + $index, 'product' => $index, 'branch' => 0, 'plan' => '', 'roadmap' => ''))->exec();
}

su('admin');

$repoTest = new repoModelTest();

$tester->session->set('repoID', 1);
r($repoTest->setHideMenuTest('execution', 101)) && p() && e('101');

$tester->session->set('repoID', 0);
r($repoTest->setHideMenuTest('execution', 102)) && p() && e('102');

$tester->session->set('repoID', 2);
r($repoTest->setHideMenuTest('project', 103)) && p() && e('103');

$tester->session->set('repoID', 3);
r($repoTest->setHideMenuTest('waterfall', 104)) && p() && e('104');

$tester->session->set('repoID', 5);
r($repoTest->setHideMenuTest('execution', 105)) && p() && e('105');
