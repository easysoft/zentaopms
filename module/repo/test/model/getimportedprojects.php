#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getImportedProjects();
timeout=0
cid=18066

- 期望返回3个项目 @3
- 期望返回空数组 @0
- 期望返回空数组 @0
- 期望返回空数组 @0
- 期望返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `serviceHost` int NOT NULL DEFAULT 0,
  `serviceProject` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'repo1', 'serviceHost' => 1, 'serviceProject' => '100', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '2', 'name' => 'repo2', 'serviceHost' => 1, 'serviceProject' => '200', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '1', 'name' => 'repo3', 'serviceHost' => 2, 'serviceProject' => '300', 'status' => 'active', 'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '2', 'name' => 'repo4', 'serviceHost' => 3, 'serviceProject' => '400', 'status' => 'importing', 'deleted' => 0),
    array('id' => 5, 'spaceID' => 1, 'product' => '1', 'name' => 'repo5', 'serviceHost' => 1, 'serviceProject' => '500', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData) $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试步骤1：正常查询存在版本库的服务器ID为1
r(count($repoTest->getImportedProjectsTest(1))) && p() && e('3'); // 期望返回3个项目

// 测试步骤2：查询不存在版本库的服务器ID
r(count($repoTest->getImportedProjectsTest(999))) && p() && e('0'); // 期望返回空数组

// 测试步骤3：边界值测试服务器ID为0
r(count($repoTest->getImportedProjectsTest(0))) && p() && e('0'); // 期望返回空数组

// 测试步骤4：负数服务器ID测试
r(count($repoTest->getImportedProjectsTest(-1))) && p() && e('0'); // 期望返回空数组

// 测试步骤5：超大服务器ID测试
r(count($repoTest->getImportedProjectsTest(999999))) && p() && e('0'); // 期望返回空数组
