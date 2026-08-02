#!/usr/bin/env php
<?php

/**
title=测试 repoModel::processGitService();
timeout=0
cid=18089

- 步骤1：正常处理Gitlab版本库
- 步骤1：正常处理 Gitlab 版本库
- 步骤1：正常处理代码库并使用配置中的 GitFox 客户端 @1
- 步骤2：GitFox 代码路径由真实 API 返回 @1
- 步骤3：普通 Git 服务统一使用配置中的 GitFox 客户端 @1
- 步骤4：API 路径使用配置中的 GitFox 地址 @1
- 步骤5：空 serviceHost 时保留 GitFox 服务信息 @1
- 步骤6：无效本地路径仍返回 API 代码路径 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `serviceProject` varchar(255) NOT NULL DEFAULT '',
  `serviceHost` varchar(50) NOT NULL DEFAULT '',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'private',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
zenData('ops_repouser')->gen(0);

$repoTable = zenData('ops_repo');
$repoTable->id->range('1,2,4');
$repoTable->spaceID->range('1');
$repoTable->product->range('1');
$repoTable->name->range('repo1,repo2,repo4');
$repoTable->path->range('http://repo.local/repo1,http://repo.local/repo2,/tmp/repo4');
$repoTable->SCM->range('Gitlab,Gitlab,GitFox');
$repoTable->scmType->range('git');
$repoTable->serviceProject->range('2,1,4');
$repoTable->serviceHost->range('1');
$repoTable->gitUID->range('uid1,uid2,uid4');
$repoTable->acl->range('private');
$repoTable->status->range('active');
$repoTable->deleted->range('0');
$repoTable->gen(3);

$repoUserTable = zenData('ops_repouser');
$repoUserTable->repo->range('1,2,4');
$repoUserTable->account->range('admin');
$repoUserTable->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repo = new repoModelTest();
$repo->seedGitFoxEntry();

// 5. 测试步骤
r($repo->processGitServiceConfigStatusTest(1)) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(4, 'codePath')) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(2)) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(1, 'apiPath')) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(4, 'emptyHost')) && p() && e('1');
r($repo->processGitServiceConfigStatusTest(1, 'invalid')) && p() && e('1');
