#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getRepoListByUrl();
timeout=0
cid=18077

- 执行repoTest模块的getRepoListByUrlTest方法，参数是'' 属性message @Url is empty.
- 执行repoTest模块的getRepoListByUrlTest方法，参数是$falseUrl 属性message @Url is empty.
- 执行repoTest模块的getRepoListByUrlTest方法，参数是$nullUrl 属性message @Url is empty.
- 执行repoTest模块的getRepoListByUrlTest方法，参数是$zeroInt 属性message @Url is empty.
- 执行repoTest模块的getRepoListByUrlTest方法，参数是'0' 属性message @Url is empty.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh;
$dbh->exec('DROP TABLE IF EXISTS `ops_provider`');
$dbh->exec('DROP TABLE IF EXISTS `ops_pipelinecontent`');
$dbh->exec('DROP TABLE IF EXISTS `ops_pipeline`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$dbh->exec(<<<'SQL'
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
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_pipelinecontent` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pipelineID` int unsigned NOT NULL DEFAULT 0,
  `variables` text DEFAULT NULL,
  `data` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_provider` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

// 登录管理员用户
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 测试步骤1：空URL输入测试
r($repoTest->getRepoListByUrlTest('')) && p('message') && e('Url is empty.');

// 测试步骤2：false 类型 URL 处理
$falseUrl = false;
r($repoTest->getRepoListByUrlTest($falseUrl)) && p('message') && e('Url is empty.');

// 测试步骤3：NULL类型URL处理（转换为空字符串）
$nullUrl = null;
r($repoTest->getRepoListByUrlTest($nullUrl)) && p('message') && e('Url is empty.');

// 测试步骤4：整数 0 处理
$zeroInt = 0;
r($repoTest->getRepoListByUrlTest($zeroInt)) && p('message') && e('Url is empty.');

// 测试步骤5：字符串 0 处理
r($repoTest->getRepoListByUrlTest('0')) && p('message') && e('Url is empty.');
