#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->batchCreate();
timeout=0
cid=18029

- 执行repo模块的batchCreateTest方法，参数是array  @1
- 执行repo模块的batchCreateTest方法，参数是array  @1
- 执行repo模块的batchCreateTest方法，参数是array  @1
- 执行repo模块的batchCreateTest方法，参数是array  @1
- 执行repo模块的batchCreateTest方法，参数是array  @1

*/

$_SERVER['REQUEST_URI'] = 'http://unittest.com';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `space` int NOT NULL DEFAULT 0,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `serviceHost` varchar(255) NOT NULL DEFAULT '',
  `serviceProject` varchar(255) NOT NULL DEFAULT '',
  `projects` varchar(255) NOT NULL DEFAULT '',
  `account` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `encrypt` varchar(30) NOT NULL DEFAULT 'base64',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  `encoding` varchar(32) NOT NULL DEFAULT 'utf-8',
  `client` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(500) NOT NULL DEFAULT '',
  `uid` varchar(32) NOT NULL DEFAULT '',
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$repo1 = (object)array('space' => 1, 'serviceProject' => 1, 'product' => 1, 'name' => 'imortRepo1', 'projects' => 1);
$repo2 = (object)array('space' => 1, 'serviceProject' => 2, 'product' => 2, 'name' => 'imortRepo2', 'projects' => 2);
$repo3 = (object)array('space' => 2, 'serviceProject' => 3, 'product' => 1, 'name' => 'imortRepo3', 'projects' => 3);
$repo4 = (object)array('space' => 2, 'serviceProject' => 4, 'product' => 2, 'name' => 'imortRepo4', 'projects' => 4);
$repo5 = (object)array('space' => 3, 'serviceProject' => 5, 'product' => 3, 'name' => 'imortRepo5', 'projects' => 5);

$repo = new repoModelTest();
baseRouter::$loadedTargets['model'][$repo->instance->appName]['instance'] = new stdclass();

r($repo->batchCreateTest(array($repo1), 1, 'Git')) && p() && e('1');
r($repo->batchCreateTest(array($repo2), 1, 'Git')) && p() && e('1');
r($repo->batchCreateTest(array($repo3), 1, 'Git')) && p() && e('1');
r($repo->batchCreateTest(array($repo4), 1, 'Git')) && p() && e('1');
r($repo->batchCreateTest(array($repo5), 1, 'Git')) && p() && e('1');