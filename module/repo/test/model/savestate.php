#!/usr/bin/env php
<?php

/**

title=测试 repoModel::saveState();
timeout=0
cid=18101

- 步骤1：正常设置有效的代码库ID @2
- 步骤2：设置无效的代码库ID @1
- 步骤3：不传入代码库ID且session中无repoID @1
- 步骤4：在project tab下设置代码库ID @1
- 步骤5：测试边界值repoID为0的情况 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_spaceuser`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
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
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_spaceuser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `space` int unsigned NOT NULL DEFAULT 0,
  `role` varchar(10) NOT NULL DEFAULT '',
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$tester->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq(11)->exec();
$tester->dao->delete()->from(TABLE_ENTRY)->where('code')->eq('gitfox')->exec();

$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'repo1', 'gitUID' => 'uid1', 'acl' => 'open', 'status' => 'active', 'deleted' => 0))->exec();
$tester->dao->insert(TABLE_REPO)->data((object)array('id' => 2, 'spaceID' => 1, 'product' => '2', 'name' => 'repo2', 'gitUID' => 'uid2', 'acl' => 'open', 'status' => 'active', 'deleted' => 0))->exec();
$tester->dao->insert('ops_spaceuser')->data((object)array('space' => 1, 'role' => 'manager', 'account' => 'admin'))->exec();
$tester->dao->insert(TABLE_PROJECTPRODUCT)->data((object)array('project' => 11, 'product' => 1, 'branch' => 0, 'plan' => '', 'roadmap' => ''))->exec();
$tester->dao->insert(TABLE_ENTRY)->data((object)array(
    'name'        => 'GitFox',
    'account'     => 'admin',
    'code'        => 'gitfox',
    'key'         => 'gitfox',
    'freePasswd'  => 0,
    'ip'          => '*',
    'createdBy'   => 'admin',
    'createdDate' => '2026-01-01 00:00:00',
    'calledTime'  => 0,
    'editedBy'    => 'admin',
    'editedDate'  => '2026-01-01 00:00:00',
    'deleted'     => 0,
))->exec();

su('admin');

$repo = new repoModelTest();

r($repo->saveStateTest(2)) && p() && e('2'); // 步骤1：正常设置有效的代码库ID
r($repo->saveStateTest(10001)) && p() && e('1'); // 步骤2：设置无效的代码库ID
r($repo->saveStateTest()) && p() && e('1'); // 步骤3：不传入代码库ID且session中无repoID
$repo->objectModel->app->tab = 'project';
r($repo->saveStateTest(2, 11)) && p() && e('1'); // 步骤4：在project tab下设置代码库ID
r($repo->saveStateTest(0)) && p() && e('1'); // 步骤5：测试边界值repoID为0的情况
