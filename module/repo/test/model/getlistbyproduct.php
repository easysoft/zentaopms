#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getListByProduct();
timeout=0
cid=18071

- 测试productID=1验证第1条name >> repo1
- 测试productID=2验证第3条name >> repo3
- 测试productID=1 limit=2验证第1条name >> repo1
- 测试无效productID返回空 >> 0
- 测试productID=0返回空 >> 0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec("CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$tester->dao->exec("INSERT INTO `ops_repo` VALUES (1,1,'1','repo1','Git','active',0)");
$tester->dao->exec("INSERT INTO `ops_repo` VALUES (2,1,'1','repo2','Git','active',0)");
$tester->dao->exec("INSERT INTO `ops_repo` VALUES (3,1,'2','repo3','Gitlab','active',0)");
$tester->dao->exec("INSERT INTO `ops_repo` VALUES (4,1,'1','repo4','SVN','active',0)");
$tester->dao->exec("INSERT INTO `ops_repo` VALUES (5,1,'3','repo5','Gitlab','active',0)");

su('admin');
$repoTest = new repoModelTest();

r($repoTest->getListByProductTest(1)) && p('1:name') && e('repo1');
r($repoTest->getListByProductTest(2)) && p('3:name') && e('repo3');
r($repoTest->getListByProductTest(1,2)) && p('1:name') && e('repo1');
r($repoTest->getListByProductCountTest(999)) && p() && e('0');
r($repoTest->getListByProductCountTest(0)) && p() && e('0');
