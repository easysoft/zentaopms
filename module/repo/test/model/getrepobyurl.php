#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getRepoByUrl();
timeout=0
cid=18075

- 执行repoTest模块的getRepoByUrlTest方法，参数是'' 属性message @Url is empty.
- 执行repoTest模块的getRepoByUrlTest方法，参数是$nullUrl 属性message @Url is empty.
- 执行repoTest模块的getRepoByUrlTest方法，参数是$falseUrl 属性message @Url is empty.
- 执行repoTest模块的getRepoByUrlTest方法，参数是$zeroInt 属性message @Url is empty.
- 执行repoTest模块的getRepoByUrlTest方法，参数是'0' 属性message @Url is empty.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_provider`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_pipelinecontent`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_pipeline`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_pipeline` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `repoID` int unsigned NOT NULL DEFAULT 0,
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `engine` varchar(30) NOT NULL DEFAULT '',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_pipelinecontent` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pipelineID` int unsigned NOT NULL DEFAULT 0,
  `variables` text DEFAULT NULL,
  `data` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_provider` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

// 模拟用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试步骤1：使用空URL
r($repoTest->getRepoByUrlTest('')) && p('message') && e('Url is empty.');

// 测试步骤2：使用 null 输入
$nullUrl = null;
r($repoTest->getRepoByUrlTest($nullUrl)) && p('message') && e('Url is empty.');

// 测试步骤3：使用 false 输入
$falseUrl = false;
r($repoTest->getRepoByUrlTest($falseUrl)) && p('message') && e('Url is empty.');

// 测试步骤4：使用整数 0
$zeroInt = 0;
r($repoTest->getRepoByUrlTest($zeroInt)) && p('message') && e('Url is empty.');

// 测试步骤5：使用字符串 0
r($repoTest->getRepoByUrlTest('0')) && p('message') && e('Url is empty.');
